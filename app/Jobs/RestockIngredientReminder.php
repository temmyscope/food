<?php

namespace App\Jobs;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function __construct(protected Ingredient $podcast){}
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    }
}
