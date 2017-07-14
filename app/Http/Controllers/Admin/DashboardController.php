<?php
namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\Listing;
use App\Models\User_Rating;
use App\Models\Game;
use App\Models\User;
use Charts;

class DashboardController
{
    public function index()
    {
        $this->data['chart_listing'] = Charts::database(Listing::all(), 'area', 'chartjs')
            ->elementLabel("Listings")
            ->title("Listings last 7 days")
            ->colors(['#00c0ef'])
            ->responsive(false)
            ->height(300)
            ->width(0)
            ->lastByDay();

        $this->data['chart_offer'] = Charts::database(Offer::all(), 'area', 'chartjs')
            ->elementLabel("Offers")
            ->title("Offers last 7 days")
            ->colors(['#00a65a'])
            ->responsive(false)
            ->height(300)
            ->width(0)
            ->lastByDay();

        $this->data['chart_user'] = Charts::database(User::all(), 'area', 'chartjs')
            ->elementLabel("Users")
            ->title("User registrations last 7 days")
            ->colors(['#f39c12'])
            ->responsive(false)
            ->height(300)
            ->width(0)
            ->lastByDay();

        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['users'] = User::orderBy('created_at', 'desc')->get(); // get users
        $this->data['listings'] = Listing::all(); // get listings
        $this->data['offers'] = Offer::all(); // get listings
        $this->data['games'] = Game::all(); // get listings
				
        // Install security check
        $this->data['security'] = substr(sprintf('%o', fileperms(base_path('.env'))), -4) >= '0755' || substr(sprintf('%o', fileperms(base_path('config/app.php'))), -4) >= '0755';
        // Check if giantbomb id is set
        $this->data['giantbomb'] = strlen(config('settings.giantbomb_key')) <= 0;

        return view('backpack::dashboard', $this->data);
    }
}
