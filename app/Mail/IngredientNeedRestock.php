<?php

namespace App\Mail;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;

class IngredientNeedRestock extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  App\Models\Ingredient  $ingredient
     * @return void
     */
    public function __construct(protected Ingredient $ingredient)
    {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Ingredient Needs Restock',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function content()
    {
        return (new MailMessage)
                ->greeting('Hello! Admin')
                ->line("One of your ingredients, {$this->ingredient->name} is now below 50%! of its initial quantity.")
                ->line('Thank you for using our application!');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
