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

    $orderItems = [];

    foreach( $request->products as $productData ){
      //$product = Product::with(['ingredients'])->where('id', $productData->product_id)->first();

      $product_ingredients = ProductIngredient::where('product_id', $productData['product_id'])->get();

      foreach ($product_ingredients as $product_ingredient) {
        $ingredient = Ingredient::find($product_ingredient->ingredient_id);

        //check if the ingredient available is enough to produce the qty of burgers (products) ordered
        if ( ($product_ingredient->quantity * $productData['quantity']) <= $ingredient->available_qty ) {
          //Add OrderItem to the order
          array_push($orderItems, new OrderItem($productData) );
          //reduce the available quantity
          $ingredient->available_qty -= $product_ingredient->quantity * $productData['quantity'];
          $ingredient->save();
        }else{
          throw ValidationException::withMessages([
            'errors' => [
              'product' => "Insufficient ingredients to make product with id:{$productData['product_id']}"
            ],
          ]);
        }
      }
    }

    $order->items()->saveMany($orderItems);
    $order->refresh();

    return $this->respondWithSuccess(code:201, data: ['order' => $order->items]);
  }

}