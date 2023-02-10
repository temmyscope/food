<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Jobs\RestockIngredientReminder;

class IngredientObserver
{
    
    /**
     * Handle the Ingredient "updated" event.
     *
     * @param  \App\Models\Device  $comment
     * @return void
    **/
    public function updated(Ingredient $ingredient)
    {
        if ( 
            $ingredient->needs_restock == "false" &&
            is50PercentOrLess($ingredient->initial_qty, $ingredient->available_qty) 
        ) {
            //set to true, so future updates don't trigger dispatch
            $ingredient->needs_restock = 'true';
            $ingredient->saveQuietly();

            // queue job to send mail to admin
            RestockIngredientReminder::dispatch($ingredient);
        }
    }

}
