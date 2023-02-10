<?php
namespace App\Repository\Concrete;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductIngredient;
use App\Http\Requests\OrderRequest;
use App\Models\Ingredient;
use App\Repository\Interface\OrderRepositoryInterface;
use App\Traits\RequestResponseTrait;
use Illuminate\Validation\ValidationException;

class OrderRepository implements OrderRepositoryInterface
{

  use RequestResponseTrait;

  public function create(OrderRequest $request){
    //create order
    $order = new Order();
    $order->save();

    $order_items = [];

    foreach( $request->products as $product_data ){
      $product_ingredients = ProductIngredient::where('product_id', $product_data['product_id'])->get();

      foreach ($product_ingredients as $product_ingredient) {
        $ingredient = Ingredient::find($product_ingredient->ingredient_id);

        //check if the ingredient available is enough to produce the qty of burgers (products) ordered
        if ( ($product_ingredient->quantity * $product_data['quantity']) <= $ingredient->available_qty ) {
          //reduce the available quantity
          $ingredient->available_qty -= $product_ingredient->quantity * $product_data['quantity'];
          
          //check if stock only just went down to or below 50% 
          if (
            $ingredient->needs_restock == "false" &&
            is50PercentOrLess($ingredient->initial_qty, $ingredient->available_qty) 
          ){
            //set to true, so future updates don't trigger dispatch, except new stock has been added 
            $ingredient->needs_restock = 'true';
            $ingredient->save();
          }else{
            $ingredient->saveQuietly();
          }

        }else{
          return $this->respondWithError(code:422, data: [
            'errors' => [
              'product' => "Insufficient `{$ingredient->name}` to make product with id: {$product_data['product_id']}"
            ]
          ]);
        }
      }

      //Add OrderItem to the order if code gets here: meaning Exception did not get thrown
      array_push($order_items, new OrderItem($product_data) );
    }

    $order->items()->saveMany($order_items);
    $order->refresh();

    return $this->respondWithSuccess(code:201, data: ['order' => $order->items]);
  }

}