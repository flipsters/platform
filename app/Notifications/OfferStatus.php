<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OfferStatus extends Notification
{
    use Queueable;

    protected $offer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->offer->declined == 0) {
            return (new MailMessage)
                ->subject(config('settings.page_name') . ': ' . trans('emails.offer.status_accepted_title', ['game_name' => $this->offer->listing->game->name, 'platform_name' => $this->offer->listing->game->platform->name, 'user_name' => $this->offer->listing->user->name]))
                ->line(trans('emails.offer.accepted_text', ['game_name' => $this->offer->listing->game->name, 'platform_name' => $this->offer->listing->game->platform->name, 'user_name' => $this->offer->listing->user->name]))
                ->action(trans('emails.offer.show_button'), route('frontend.offer.show', $this->offer->id))
                ->line(trans('emails.auth.thank_you_for_using_app', ['page_name' => config('settings.page_name')]));
        } else {
            return (new MailMessage)
                ->subject(config('settings.page_name') . ': ' . trans('emails.offer.status_declined_title', ['game_name' => $this->offer->listing->game->name, 'platform_name' => $this->offer->listing->game->platform->name, 'user_name' => $this->offer->listing->user->name]))
                ->line(trans('emails.offer.declined_text', ['game_name' => $this->offer->listing->game->name, 'platform_name' => $this->offer->listing->game->platform->name, 'user_name' => $this->offer->listing->user->name]))
                ->action(trans('emails.offer.show_button'), route('frontend.offer.show', $this->offer->id))
                ->line(trans('emails.auth.thank_you_for_using_app', ['page_name' => config('settings.page_name')]));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'listing_id' => $this->offer->listing_id,
            'offer_id' => $this->offer->id,
            'status' => $this->offer->declined == 0 ? 'accepted' : 'declined',
        ];
    }
}
