<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Jobs\RestockIngredientReminder;

class IngredientObserver
{
    
    /**
     * Handle the Ingredient "updated" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
    **/
    public function updated(Ingredient $ingredient)
    {
        // queue job to send mail to admin
        RestockIngredientReminder::dispatch($ingredient);
    }

}
