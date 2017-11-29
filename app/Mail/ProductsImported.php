<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductsImported extends Mailable
{
    use Queueable, SerializesModels;

    private $json;
    private $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arrayData, $filepath)
    {
        $this->json = json_encode($arrayData);
        $this->path = $filepath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('geovannylc@gmail.com')
                    ->view('emails.products-imported')
                    ->attach($this->path)
                    ->with('json', $this->json);
    }
}
