<?php

namespace App\Observers;

use App\Jobs\RestockIngredientReminder;
use App\Models\Ingredient;

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
    RestockIngredientReminder::dispatch($ingredient)->afterCommit();
  }

}
