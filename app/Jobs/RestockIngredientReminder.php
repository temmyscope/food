<?php

namespace App\Jobs;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use App\Mail\IngredientNeedRestock;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RestockIngredientReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     *
     * @param  App\Models\Ingredient  $ingredient
     * @return void
     */
    public function __construct(protected Ingredient $ingredient){}
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to("admin email")->send(
            new IngredientNeedRestock($this->ingredient)
        );
    }
}
