<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Jobs\RestockIngredientReminder;
use function App\Helpers\is50PercentOrLess;

class IngredientObserver
{
    
  /**
   * Handle the Ingredient "updated" event.
   *
   * @param  \App\Models\Device  $comment
   * @return void
   */
  public function updated(Ingredient $ingredient)
  {
    /**
     * update ingredient's needs_restock field to true and queue job to send mail
     *  to the admin if ingredient's available qty drops to or below 50% of the initial qty
    **/
    if ( is50PercentOrLess($ingredient->initial_qty, $ingredient->available_qty) &&  $ingredient->needs_restock) {
        $ingredient->needs_restock = true;
        $ingredient->saveQuietly();

        RestockIngredientReminder::dispatch($ingredient)->afterCommit();
    }
  }

}
