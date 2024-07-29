<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class pdfGenerate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $user_data;
    public $product_data;
    /**
     * Create a new job instance.
     */
    public function __construct($order,$user_data,$product_data)
    {
        $this->order=$order;
        $this->user_data=$user_data;
        $this->product_data=$product_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $pdf = Pdf::loadView('invoices.invoice',['order'=>$this->order,'user_data'=>$this->user_data,'product_data'=>$this->product_data]);
      $pdfPath ='invoices/invoice-'.$this->order->uuid.'.pdf';
      storage::put($pdfPath ,$pdf->output());
     }
}
