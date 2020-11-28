<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $link;
    private $linkProducts;

    public function __construct($link, $linkProducts)
    {
        $this->link = $link;
        $this->linkProducts = $linkProducts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Link::create($this->link);

        LinkProduct::insert($this->linkProducts);
    }
}
