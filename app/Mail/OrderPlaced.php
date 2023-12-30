<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $settings;
    public $productName;
    public $quantity;
    public $totalPrice;
    /**
     * Create a new message instance.
     */
    public function __construct( public Order $order, )
    {
        $this->settings = Settings::first();

        $product = $order?->items[0]->product?->name;

        if($order?->items[0]->options){
            foreach($order?->items[0]->options as $key => $value){

                if(is_string($value) && is_string($key)){

                    $product .=' '.$key.' : '.$value;
                }
            }

        }


        // $this->totalPrice = 0;
        $this->totalPrice = number_format(($order?->items[0]['quantity'] * $order?->items[0]['unit_price'] ) + $order?->shipping_price , 0).' DZD';

        // $this->quantity =1;
        $this->quantity = $order?->items[0]['quantity'];

        $this->productName = $product;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        $subject = $this->order->customer->name;

        $subject .= " a passé une commande #".$this->order->id;

        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', ''), env('MAIL_FROM_NAME', '')),
            subject: $subject
        );


    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.order-placed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
