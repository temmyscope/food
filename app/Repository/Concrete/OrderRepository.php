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
          //Add OrderItem to the order
          array_push($order_items, new OrderItem($product_data) );
          //reduce the available quantity
          $ingredient->available_qty -= $product_ingredient->quantity * $product_data['quantity'];
          $ingredient->save();
        }else{
          throw ValidationException::withMessages([
            'errors' => [
              'product' => "Insufficient ingredients to make product with id:{$product_data['product_id']}"
            ],
          ]);
        }
      }
    }

    $order->items()->saveMany($order_items);
    $order->refresh();

    return $this->respondWithSuccess(code:201, data: ['order' => $order->items]);
  }

}