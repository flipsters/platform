<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Offer;
use App\Models\Listing;
use App\Models\User_Rating;
use App\Models\Game;
use App\Models\User;
use App\Models\Report;
use App\Models\Payment;
use App\Models\Transaction;
use App\Notifications\OfferNew;
use App\Notifications\OfferDeleted;
use App\Notifications\OfferStatus;
use App\Notifications\MessageNew;
use App\Notifications\RatingNew;
use App\Notifications\PaymentNew;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Validator;
use Redirect;
use Auth;
use SEO;
use Config;
use Omnipay\Omnipay;
use ClickNow\Money\Money;

class OfferController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Offer overview.
     *
     * @param  int  $id
     * @return view
     */
    public function show($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/login');
        }

        $offer =  Offer::withTrashed()->find($id);
        // check if offer exist or is deleted
        if (!$offer) {
            return abort('404');
        }

        $listing = Listing::with('game', 'user', 'game.giantbomb', 'game.platform')->withTrashed()->find($offer->listing_id);

        // check if listing exist
        if (!$listing) {
            return abort('404');
        }

        // Select User
        if (Auth::user()->id == $offer->user_id) {
            $user = $listing->user;
        } else {
            $user = $offer->user;
        }

        // Check if user is logged in
        if (!(Auth::user()->id == $offer->user_id || Auth::user()->id == $listing->user_id)) {
            // Check if offer reported and user is staff member
            if (!$offer->reported && !Auth::user()->can('edit_offers')) {
                return abort('404');
            }
        }

        SEO::setTitle(trans('general.title.offer', ['page_name' => config('settings.page_name'), 'platform' => $listing->game->platform->name, 'game_name' => $listing->game->name, 'user_name' => $offer->user->name]));

        $trade_game = Game::find($offer->trade_game);

        $thread = Thread::findOrFail($offer->thread_id);

        return view('frontend.offer.show', ['offer' => $offer, 'listing' => $listing, 'game' => $listing->game, 'trade_game' => $trade_game, 'user' => $user, 'thread' => $thread]);
    }

    /**
     * Add new offer.
     *
     * @param  request  $request
     * @return mixed
     */
    public function add(Request $request)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/login');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        // if ($request->trade_game) {
        //     $request->merge(array('game_id' => decrypt($request->game_id), 'listing_id' => decrypt($request->listing_id), 'trade_game' => decrypt($request->trade_game)));
        // } else {
            $request->merge(array('game_id' => decrypt($request->game_id), 'listing_id' => decrypt($request->listing_id)));
        // }

        $this->validate($request, [
            'game_id' => 'required|exists:games,id',
            'listing_id' => 'required|exists:listings,id',
            'trade_game' => 'exists:games,id'
        ]);

        $listing = Listing::find($request->listing_id);

        // Check if listing is available
        if ($listing->status == 1 || $listing->status == 2) {
            return Redirect::to($listing->url_slug);
        }

        // Check if logged in user want buy own listing
        if (Auth::user()->id == $listing->user_id) {
            \Alert::error('<i class="fa fa-times m-r-5"></i>' . trans('offers.alert.own_offer'))->flash();
            return Redirect::to($listing->url_slug);
        }

        // Check if listing user is not banned
        if (!$listing->user->isActive()) {
            return Redirect::to('/');
        }

        // Create new offer
        $offer = new Offer;

        // General data
        $offer->user_id = \Auth::user()->id;
        $offer->listing_id = $listing->id;
        $offer->status = '0';

        if ($listing->sell && !$request->trade_game) {
            // Check if listing accept price suggestions
            if ($listing->sell_negotiate) {
                $offer->price_offer = $request->price_suggestion;
            } else {
                $offer->price_offer = $listing->getAttribute('price');
            }
        }

        if ($request->trade_game) {
            $trade_list = json_decode($listing->trade_list, true);
            // Check if selected game is from trade list
            if (!is_null($trade_list) && array_key_exists($request->trade_game, $trade_list) && !$request->add_charge_user && !$request->add_charge_partner) {
                $offer->trade_game = $request->trade_game;
                $offer->trade_from_list = 1;
              // save additional charge
              if ($trade_list[$request->trade_game]['price_type'] != 'none') {
                  $offer->additional_type = $trade_list[$request->trade_game]['price_type'];
                  $offer->additional_charge = $trade_list[$request->trade_game]['price'];
              }
            } else {
                // check if accept game suggestions
                if ($listing->trade_negotiate) {
                    // Check if selected game is listing game
                    if ($request->trade_game == $listing->game->id) {
                        \Alert::error('<i class="fa fa-times m-r-5"></i>' . trans('offers.alert.same_game'))->flash();
                        return Redirect::to($listing->url_slug);
                    }
                    $offer->trade_game = $request->trade_game;
                    // game is not from trade list
                    $offer->trade_from_list = 0;

                    // additional_charge from partner (trade suggestions)
                    if ($request->add_charge_user && filter_var($request->add_charge_user, FILTER_SANITIZE_NUMBER_INT) > 0) {
                        $offer->additional_type = 'give';
                        // filter the float to int
                        $offer->additional_charge = abs(filter_var($request->add_charge_user, FILTER_SANITIZE_NUMBER_INT));
                    }

                    // additional_charge from partner (trade suggestions)
                    if ($request->add_charge_partner && filter_var($request->add_charge_partner, FILTER_SANITIZE_NUMBER_INT) > 0) {
                        $offer->additional_type = 'want';
                        // filter the float to int
                        $offer->additional_charge = abs(filter_var($request->add_charge_partner, FILTER_SANITIZE_NUMBER_INT));
                    }


                } else {
                    \Alert::error('<i class="fa fa-times m-r-5"></i>' . trans('offers.alert.suggestion_disabled'))->flash();
                    return Redirect::to($listing->url_slug);
                }
            }
        }

        if (!$offer->trade_game && empty($offer->price_offer)) {
            return Redirect::to($listing->url_slug);
        }


        $offer->save();

        // Send Notification to listing user
        $listing_user = User::find($listing->user_id);

        $listing_user->notify(new OfferNew($offer));

        // Open Chat
        $thread = Thread::create(
            [
                'subject' => $listing->game->name . " offer from " . \Auth::user()->name,
                'offer_id' => $offer->id
            ]
        );

        if ($offer->trade_game) {
            // Message
            Message::create(
                [
                    'thread_id' => $thread->id,
                    'user_id'   => \Auth::user()->id,
                    'body'      => trans('offers.general.chat_trade', ['game_name' => $listing->game->name, 'platform_name' => $listing->game->platform->name, 'trade_game' => $offer->game->name, 'Trade_platform' => $offer->game->platform->name]),
                ]
            );
        } else {
            // Message
            Message::create(
                [
                    'thread_id' => $thread->id,
                    'user_id'   => \Auth::user()->id,
                    'body'      => trans('offers.general.chat_buy', ['game_name' => $listing->game->name, 'platform_name' => $listing->game->platform->name, 'price' => $offer->price_offer_formatted]),
                ]
            );
        }

        // Sender
        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => \Auth::user()->id,
                'last_read' => new Carbon,
            ]
        );

        // Recipients
        $thread->addParticipant($listing->user->id);

        $offer->thread_id = $thread->id;
        $offer->save();

        // add last offer timestamp on listing
        $listing->last_offer_at = new Carbon;
        $listing->save();

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Delete offer.
     *
     * @param  request  $request
     * @return mixed
     */
    public function delete(Request $request)
    {

        // Check if logged in
        if (!(\Auth::check())) {
            return abort('404');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('offer_id' => decrypt($request->offer_id)));

        $this->validate($request, [
            'offer_id' => 'required|exists:offers,id'
        ]);

        $offer = Offer::find($request->offer_id);

        if (!$offer) {
            return abort('404');
        }

        // Check if logged in user can delete this offer
        if (!(\Auth::user()->id == $offer->user_id)) {
            return abort('404');
        }

        // Check status of listing
        if ($offer->status >= 1) {
            return abort('404');
        }

        // Notifications to listing user
        if (($offer->listing->status == 0 || is_null($offer->listing->status)) && is_null($offer->listing->deleted_at)) {
            $listing_user = User::find($offer->listing->user_id);
            $listing_user->notify(new OfferDeleted($offer));
        }

        // Save reason for trash
        $offer->declined = 1;
        $offer->decline_note ='offers.general.decline_reason_offer_deleted';
        $offer->closed_at = new Carbon;
        $offer->save();

        // show a success message
        \Alert::error('<i class="fa fa-trash m-r-5"></i>' . trans('offers.alert.deleted', ['game_name' => str_replace("'", '', $offer->listing->game->name)]))->flash();

        // delete offer
        $offer->delete();

        return redirect()->back();
    }

    /**
     * Rate user.
     *
     * @param  request  $request
     * @return mixed
     */
    public function rate(Request $request)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('offer_id' => decrypt($request->offer_id)));

        $this->validate($request, [
            'offer_id' => 'required|exists:offers,id'
        ]);

        $offer = Offer::find($request->offer_id);
        $listing = Listing::find($offer->listing_id);

        // Check if offer & listing exists
        if (!($offer || $listing)) {
            return Redirect::to('/');
        }

        // Check if logged user can review this offer
        if (!(Auth::user()->id == $offer->user_id || Auth::user()->id == $listing->user_id)) {
            return Redirect::to('/');
        }

        // Check if review already added
        if (User_Rating::where('user_id_from', Auth::user()->id)->where('offer_id', $offer->id)->exists()) {
            return Redirect::to('offer/' . $offer->id);
        }

        $rating = new User_Rating;

        // General data
        $rating->user_id_from = Auth::user()->id;
        $rating->user_id_to = Auth::user()->id == $offer->user_id ? $listing->user_id : $offer->user_id;
        $rating->rating = $request->review;
        $rating->notice = $request->review_note;

        $rating->offer_id = $offer->id;
        $rating->listing_id = $listing->id;

        $rating->save();

        if (Auth::user()->id == $offer->user_id) {
            // release money to seller
            if ($listing->payment && $offer->payment) {
                $this->transaction($offer->payment->id, $listing->user_id);
            }
            $offer->rating_id_offer = $rating->id;
            $offer->save();
        } elseif (Auth::user()->id == $listing->user_id) {
            $offer->rating_id_listing = $rating->id;
            $offer->save();
        }


        if (!is_null($offer->rating_id_listing) && !is_null($offer->rating_id_offer)) {
            $rating_listing = User_Rating::find($offer->rating_id_listing);
            $rating_listing->active = 1;
            $rating_listing->save();
            $rating_offer = User_Rating::find($offer->rating_id_offer);
            $rating_offer->active = 1;
            $rating_offer->save();
            $listing->status = 2;
            $listing->save();
            $offer->status = 2;
            $offer->closed_at = \Carbon\Carbon::now()->toDateTimeString();
            $offer->save();
            // send notifications to users
            $listing->user->notify(new RatingNew($offer, $rating_offer));
            $offer->user->notify(new RatingNew($offer, $rating_listing));
        }

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Accept offer.
     *
     * @param  request  $request
     * @return mixed
     */
    public function accept(Request $request)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('offer_id' => decrypt($request->offer_id)));

        $this->validate($request, [
            'offer_id' => 'required|exists:offers,id'
        ]);

        $offer = Offer::find($request->offer_id);
        $listing = Listing::find($offer->listing_id);

        // check listing status
        if (!($listing->status == 0 || is_null($listing->status))) {
            return abort('404');
        }

        if ($offer->status != 0) {
            return abort('404');
        }

        // Check if logged user can accept this offer
        if (!(Auth::user()->id == $listing->user_id)) {
            return Redirect::to('/');
        }

        $listing->status = 1;
        $listing->save();

        $offer->status = 1;
        $offer->save();

        // Decline all other offers
        $offers = Offer::where('listing_id', $listing->id)->where('id', '!=', $offer->id)->where('declined', 0)->get();

        foreach ($offers as $doffer) {
            $doffer->declined = 1;
            $doffer->decline_note = 'offers.general.decline_reason_another_offer';
            $doffer->closed_at = new Carbon;
            $doffer->save();
            // Send notification to offer user
            $doffer->user->notify(new OfferStatus($doffer));
        }

        // Send notification to offer user
        $offer->user->notify(new OfferStatus($offer));

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Decline offer.
     *
     * @param  request  $request
     * @return mixed
     */
    public function decline(Request $request)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('offer_id' => decrypt($request->offer_id)));

        $this->validate($request, [
            'offer_id' => 'required|exists:offers,id'
        ]);

        $offer = Offer::find($request->offer_id);
        $listing = Listing::find($offer->listing_id);

        // check listing status
        if (!($listing->status == 0 || is_null($listing->status))) {
            return abort('404');
        }

        if ($offer->status != 0) {
            return abort('404');
        }

        // Check if logged user can decline this offer
        if (!(Auth::user()->id == $listing->user_id)) {
          return Redirect::to('/');
        }

        $offer->declined = 1;
        $offer->decline_note = $request->decline_note;
        $offer->closed_at = new Carbon;
        $offer->save();

        // Send notification to offer user
        $offer->user->notify(new OfferStatus($offer));

        return Redirect::to('/offer/' . $offer->id);
    }


    /**
     * AJAX Chat for Offers Overview
     *
     * @param  integer  $id
     * @return view
     */
    public function chatOverview($id)
    {
        $offer =  Offer::withTrashed()->find($id);
        $listing = Listing::withTrashed()->with('user')->find($offer->listing_id);
        $thread = Thread::findOrFail($offer->thread_id);
        $thread->markAsRead(Auth::user()->id);

        return view('frontend.offer.chat', ['offer' => $offer, 'listing' => $listing, 'thread' => $thread]);
    }

    /**
     * Add new message
     *
     * @param  integer  $id
     * @return mixed
     */
    public function newMessage(Request $request)
    {

        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('user_id' => decrypt($request->user_id), 'thread_id' => decrypt($request->thread_id)));

        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'thread_id' => 'required|exists:messenger_threads,id'
        ]);

        $thread = Thread::findOrFail($request->thread_id);

        $offer = Offer::findOrFail($thread->offer_id);

        // Check if user is participant of thread
        if (!$thread->hasParticipant(\Auth::user()->id)) {
            // Check if user is staff member
            if (!\Auth::user()->can('edit_offers')) {
                return abort('403');
            }
        }

        $check_array = [
            'listing_id' => $offer->listing_id,
            'offer_id' => $offer->id,
            'user_id' => $request->user_id,
        ];

        $user = User::find($request->user_id);

        // notificate all participants
        foreach ($thread->users as $participant) {
            if ($participant->id != $request->user_id) {
                // get latest thread notification for the user
                $notification_check = $participant->notifications()->where('data', json_encode($check_array))->first();
                if (!$notification_check || !($notification_check->created_at->addMinutes('45') > \Carbon::now())) {
                    $participant->notify(new MessageNew($offer, $user));
                }
            }
        }

        // Create new message
        Message::create([
          'thread_id' => $request->thread_id,
          'user_id'   => $request->user_id,
          'body'      => $request->message
        ]);
    }

    /**
     * Report offer.
     *
     * @param  request  $request
     * @return mixed
     */
    public function report(Request $request)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // check if user account is active
        if (! \Auth::user()->isActive()) {
            \Auth::logout();
            return redirect('login')->with('error', trans('auth.deactivated'));
        }

        // decrypt input
        $request->merge(array('offer_id' => decrypt($request->offer_id)));

        $this->validate($request, [
            'offer_id' => 'required|exists:offers,id'
        ]);

        $offer = Offer::find($request->offer_id);
        $listing = Listing::find($offer->listing_id);

        // check offer status
        if ($offer->status == 0) {
            return abort('404');
        }

        // Check if logged user can report this offer
        if (!(Auth::user()->id == $listing->user_id) && !(Auth::user()->id == $offer->user_id)) {
            return Redirect::to('/');
        }

        // Check if offer already reported
        if ($offer->reported) {
            // show a error message
            \Alert::error('<i class="fa fa-life-ring m-r-5"></i>' . trans('offers.alert.already_reported', ['username' => $offer->report->user->name ]))->flash();
            return Redirect::to('/offer/' . $offer->id);
        }

        // Check if reason is set
        if (strlen($request->reason) == 0) {
            // show a error message
            \Alert::error('<i class="fa fa-life-ring m-r-5"></i>' . trans('offers.alert.missing_reason'))->flash();
            return Redirect::to('/offer/' . $offer->id);
        }

        // Create new report
        $report = new Report;
        // Report details
        $report->offer_id = $offer->id;
        $report->listing_id = $listing->id;
        $report->user_id = Auth::user()->id;
        $report->user_is = Auth::user()->id == $listing->user_id ? 'seller' : 'buyer';
        $report->reason = $request->reason;
        // Save report
        $report->save();

        // show a success message
        \Alert::success('<i class="fa fa-life-ring m-r-5"></i>' . trans('offers.alert.reported'))->flash();

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Ban User.
     *
     * @param  int  $id
     * @return mixed
     */
    public function reportBan($id, $user_id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // Check if user can ban users
        if (!(Auth::user()->can('edit_offers'))) {
            return Redirect::to('/');
        }

        // Get offer
        $offer = Offer::findOrFail($id);

        // Get user
        $banuser = User::findOrFail($user_id);

        // Check is user is participant of the offer
        if (!($banuser->id == $offer->listing->user_id) && !($banuser->id == $offer->user_id)) {
            return Redirect::to('/');
        }

        // Ban / Unban User
        $banuser->status = $banuser->status ? '0' : '1';
        $banuser->save();

        // show a success message
        if ($banuser->status) {
            \Alert::success('<i class="fa fa-user-times m-r-5"></i> ' . $banuser->name . ' succesfully unbaned')->flash();
        } else {
            \Alert::error('<i class="fa fa-user-times m-r-5"></i> ' . $banuser->name  .' succesfully baned')->flash();
        }

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Close offer / listing.
     *
     * @param  int  $id, string $reopen
     * @return mixed
     */
    public function reportOfferClose($id, $reopen = null)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // Check if user can ban users
        if (!(Auth::user()->can('edit_offers'))) {
            return Redirect::to('/');
        }

        // Get offer
        $offer = Offer::findOrFail($id);

        // Get Listing
        $listing = Listing::findOrFail($offer->listing->id);

        // Close or reopen listing
        if ($reopen == null) {
            $listing->status = 2;
            \Alert::error('<i class="fa fa-tag m-r-5"></i> ' . (!$offer->declined ? 'Offer &' : '') .' Listing closed!')->flash();
        } else {
            $listing->status = 0;
            \Alert::success('<i class="fa fa-tag m-r-5"></i> ' . (!$offer->declined ? 'Offer closed &' : '') . ' Listing reopened!')->flash();
        }

        $listing->save();

        if (!$offer->declined) {
            // Close offer
            $offer->declined = 1;
            $offer->decline_note = 'offers.general.decline_reason_staff';
            $offer->closed_at = new Carbon;
            // Save offer
            $offer->save();
        }

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Close report.
     *
     * @param  int  $id
     * @return mixed
     */
    public function reportClose($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // Check if user can ban users
        if (!(Auth::user()->can('edit_offers'))) {
            return Redirect::to('/');
        }

        // Get offer
        $offer = Offer::findOrFail($id);

        // Check if offer is reported
        if ($offer->reported) {
            $report = $offer->report;

            $report->user_staff = Auth::user()->id;

            $report->status = $report->status ? 0 : 1;
            $report->closed_at = $report->status ? new Carbon : null;

            $report->save();
        }

        // show a success message
        if ($report->status) {
            \Alert::success('<i class="fa fa-life-ring m-r-5"></i> Report closed!')->flash();
        } else {
            \Alert::error('<i class="fa fa-life-ring m-r-5"></i> Report opened!')->flash();
        }

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Close report.
     *
     * @param  int  $id
     * @return mixed
     */
    public function reportRevoke($id, $rating_id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // Check if user can ban users
        if (!(Auth::user()->can('edit_offers'))) {
            return Redirect::to('/');
        }

        // Get offer
        $offer = Offer::findOrFail($id);

        $rating = User_Rating::findOrFail($rating_id);

        // Check if rating is from offer
        if (($rating->id != $offer->rating_id_listing) && ($rating->id != $offer->rating_id_offer)) {
            return Redirect::to('/');
        }

        $rating->active = $rating->active ? 0 : 1;

        $rating->save();

        // show a success message
        if ($rating->active) {
            \Alert::success('<i class="fa fa-repeat m-r-5"></i> Rating from ' . $rating->user_from->name . ' activated!')->flash();
        } else {
            \Alert::error('<i class="fa fa-repeat m-r-5"></i> Rating from ' . $rating->user_from->name . ' revoked!')->flash();
        }

        return Redirect::to('/offer/' . $offer->id);
    }

    /**
     * Close report.
     *
     * @param  int  $id
     * @return mixed
     */
    public function reportShow($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // Check if user can ban users
        if (!(Auth::user()->can('edit_offers'))) {
            return Redirect::to('/');
        }

        // Get offer
        $report = Report::findOrFail($id);

        return Redirect::to('/offer/' . $report->offer->id);
    }

    /**
     * Post payment
     *
     * @param  int  $id
     * @return mixed
     */
    public function pay($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        $offer =  Offer::withTrashed()->find($id);
        // check if offer exist or is deleted
        if (!$offer) {
            return abort('404');
        }

        $listing = Listing::with('game', 'user', 'game.giantbomb', 'game.platform')->withTrashed()->find($offer->listing_id);

        // check if listing exist
        if (!$listing) {
            return abort('404');
        }

        // check if user is offer user
        if (Auth::user()->id != $offer->user_id) {
            \Alert::error('<i class="fa fa-times m-r-5"></i> ' . trans('payment.alert.canceled'))->flash();
            return $this->show($id);
        }

        // check if offer already paid
        if ($offer->payment && $offer->payment->status) {
            \Alert::error('<i class="fa fa-times m-r-5"></i> ' . trans('payment.alert.already_paid'))->flash();
            return $this->show($id);
        }

        $gateway = Omnipay::create('PayPal_Rest');

        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('settings.paypal_client_id'),
            'secret'   => config('settings.paypal_client_secret'),
            'testMode' => config('settings.paypal_sandbox'),
        ));


        $items[] = array(
            'name' => $listing->game->name . ' (' . $listing->game->platform->name . ')',
            'description' => trans('listings.general.condition') .': '. $listing->condition_string . ' - ' .  trans('payment.sold_by', ['username' => $listing->user->name, 'country' => $listing->user->location->country_abbreviation,'place' => $listing->user->location->place]),
            'price' => str_replace(',', '.',money($offer->price_offer, Config::get('settings.currency'))->format(false)),
            'quantity' => '1'
        );


        $params = array(
            'cancelUrl' => url('offer/' . $offer->id . '/pay/cancel'),
            'returnUrl' => url('offer/' . $offer->id . '/pay/success'),
            'currency' => Config::get('settings.currency'),
            'shippingAmount' => str_replace(',', '.', money($listing->delivery_price, Config::get('settings.currency'))->format(false)),
            'amount' => str_replace(',', '.', money($offer->price_offer, Config::get('settings.currency'))->format(false)) + str_replace(',', '.', money($listing->delivery_price, Config::get('settings.currency'))->format(false))
        );

        // Put params on the session
        Session::put('params', $params);
        Session::save();

        try {
            $response = $gateway->purchase($params)->setItems($items)->send();


            if ($response->isRedirect()) :

                $response->redirect();

            elseif ($response->isSuccessful()) :



            else :
                //do something with an error
                return $response->getMessage();

            endif;

        } catch (\Exception $e) {

            //do something with this if an error has occurred
            return $e;
        }

    }

    /**
     * Cancel payment
     *
     * @param  int  $id
     * @return mixed
     */
    public function payCancel($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        if (Session::has('params')) {
            \Alert::error('<i class="fa fa-times m-r-5"></i> ' . trans('payment.alert.canceled'))->flash();
            Session::forget('params');
        }

        return $this->show($id);
    }

    /**
     * Success payment
     *
     * @param  int  $id
     * @return mixed
     */
    public function paySuccess($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/login');
        }

        $offer =  Offer::withTrashed()->find($id);
        // check if offer exist or is deleted
        if (!$offer) {
            return abort('404');
        }

        // Check for active payment session
        if (!Session::has('params')) {
            return $this->show($id);
        }

        $gateway = Omnipay::create('PayPal_Rest');

        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('settings.paypal_client_id'),
            'secret'   => config('settings.paypal_client_secret'),
            'testMode' => config('settings.paypal_sandbox'),
        ));

        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];


        $response = $gateway->completePurchase(array(
            'payer_id'             => $payerId,
            'transactionReference' => $paymentId,
        ))->send()->getData();

        // check if payment is approved
        if ($response['state'] == 'approved') {
            // Create new payment
            $payment = new Payment;

            // Offer details
            $payment->item_id = $offer->id;
            $payment->item_type = Offer::class;

            // Page User
            $payment->user_id = Auth::user()->id;

            // Transaction details from gateway
            $payment->transaction_id = $response['transactions']['0']['related_resources']['0']['sale']['id'];
            $payment->payment_method = $response['payer']['payment_method'];
            $payment->payer_info = json_encode($response['payer']['payer_info']);

            // Money
            $payment->total = $response['transactions']['0']['amount']['total'];
            $payment->transaction_fee = $response['transactions']['0']['related_resources']['0']['sale']['transaction_fee']['value'];
            $payment->currency = $response['transactions']['0']['amount']['currency'];

            // Save payment
            $payment->save();

            // Send notification to seller
            $offer->listing->user->notify(new PaymentNew($offer, $payment));

            \Alert::success('<i class="fa fa-check m-r-5"></i> ' . trans('payment.alert.successful'))->flash();
        }

        Session::forget('params');

        return $this->show($id);

    }

    /**
     * Refund payment
     *
     * @param  int  $id
     * @return mixed
     */
    public function payRefund($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return abort('404');
        }

        // check if user can edit payments
        if (!(Auth::user()->can('edit_payments'))) {
            return abort('404');
        }

        $offer =  Offer::withTrashed()->find($id);
        // check if offer exist or is deleted
        if (!$offer) {
            return abort('404');
        }

        $payment = Payment::where('item_type', Offer::class)->where('item_id', $offer->id)->where('status', '1')->first();

        // check if payment exists
        if (!$payment) {
            \Alert::error('<i class="fa fa-times m-r-5"></i> This payment dont exists or is already refunded!')->flash();
            return Redirect::to('offer/' . $id);
        }

        // check if payment has transactions
        $transaction_check = Transaction::where('payment_id', $payment->id)->first();

        if ($transaction_check) {
          \Alert::error('<i class="fa fa-times m-r-5"></i> Money already sent to the seller! Refund is not possible anymore.')->flash();
          return $this->show($id);
        }

        $gateway = Omnipay::create('PayPal_Rest');

        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('settings.paypal_client_id'),
            'secret'   => config('settings.paypal_client_secret'),
            'testMode' => config('settings.paypal_sandbox'),
        ));

        $response = $gateway->refund()->setTransactionReference($payment->transaction_id)->send();

        // check if payment is approved
        if ($response->isSuccessful()) {
            $payment->status = '0';
            $payment->save();
            \Alert::success('<i class="fa fa-check m-r-5"></i> ' . trans('payment.alert.refunded'))->flash();
        }

        return Redirect::to('offer/' . $id);
    }

    /**
     * Refund payment
     *
     * @param  int  $id
     * @return mixed
     */
    public function payRelease($id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return abort('404');
        }

        // check if user can edit payments
        if (!(Auth::user()->can('edit_payments'))) {
            return abort('404');
        }

        $offer =  Offer::withTrashed()->find($id);
        // check if offer exist or is deleted
        if (!$offer) {
            return abort('404');
        }

        $payment = Payment::where('item_type', Offer::class)->where('item_id', $offer->id)->where('status', '1')->first();

        // check if payment exists
        if (!$payment) {
            \Alert::error('<i class="fa fa-times m-r-5"></i> This payment dont exists or is already refunded!')->flash();
            return Redirect::to('offer/' . $id);
        }

        // release money to seller
        if ($offer->payment) {
            if ($this->transaction($offer->payment->id, $offer->listing->user_id)) {
                \Alert::success('<i class="fa fa-check m-r-5"></i> Money released to seller!')->flash();
            };
        }

        return Redirect::to('offer/' . $id);
    }

    /**
     * Transaction to seller
     *
     * @param  int  $id
     * @return mixed
     */
    public function transaction($id, $user_id)
    {
        // Check if user is logged in
        if (!(Auth::check())) {
            return Redirect::to('/');
        }

        // get payment
        $payment =  Payment::find($id);

        // check if payment exist or is deleted
        if (!$payment) {
            return false;
        }

        // check if payment was not refunded
        if (!$payment->status) {
            return false;
        }

        // get user
        $user = User::find($user_id);

        // check if user exist
        if (!$user) {
            return false;
        }

        // check if transaction already exist
        $sale_transaction_check = Transaction::where('item_id', $payment->item_id)->where('item_type', $payment->item_type)->first();

        if ($sale_transaction_check) {
            return false;
        }

        $fee = round($payment->total * (config('settings.variable_fee') / 100), 2) + config('settings.fixed_fee');

        // sale transaction
        $sale_transaction = new Transaction;

        $sale_transaction->type = 'sale';
        $sale_transaction->item_id = $payment->item_id;
        $sale_transaction->item_type = $payment->item_type;
        $sale_transaction->user_id = $user->id;
        $sale_transaction->payment_id = $payment->id;
        $sale_transaction->payer_id = $payment->user_id;
        $sale_transaction->total = $payment->total - $fee;
        $sale_transaction->currency = $payment->currency;

        $sale_transaction->save();

        // add sale total to user balance
        $user->balance += $sale_transaction->total;
        $user->save();

        // fee transaction
        $fee_transaction = new Transaction;

        $fee_transaction->type = 'fee';
        $fee_transaction->item_id = $payment->item_id;
        $fee_transaction->item_type = $payment->item_type;
        $fee_transaction->user_id = $user_id;
        $fee_transaction->payment_id = $payment->id;
        $fee_transaction->payer_id = $payment->user_id;
        $fee_transaction->total = $fee;
        $fee_transaction->currency = $payment->currency;

        $fee_transaction->save();

        return true;

    }

}
