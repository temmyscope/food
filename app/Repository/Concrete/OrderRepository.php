<?php
namespace App\Repository\Concrete;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ingredient;
use App\Models\ProductIngredient;
use App\Traits\RequestResponseTrait;
use Illuminate\Validation\ValidationException;
use App\Repository\Interface\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{

    use RequestResponseTrait;

    public function create(array $products){

        [ $product_ids, $product_and_qty, $order_items ] = $this->preProcessProducts($products);

        $product_ingredients = ProductIngredient::whereIn('product_id', $product_ids)->groupBy('product_id')->get();

        foreach( $product_ingredients as $product_id => $product_ingredient ){

            foreach ($product_ingredients as $product_ingredient) {
                $ingredient = Ingredient::find($product_ingredient->ingredient_id);

                //check if the ingredient available is enough to produce the qty of burgers (products) ordered
                if ( ( $product_ingredient->quantity * $product_and_qty[$product_id] ) <= $ingredient->available_qty ) {

                    $this->updateIngredientStock($ingredient, $product_ingredient, $product_and_qty[$product_id]);

                }else{
                    return $this->respondWithError(code:422, data: [
                        'errors' => [ 
                            'product' => "Insufficient `{$ingredient->name}` to make product with id: {$$product_id}"
                        ]
                    ]);
                }
            }
        }

        //create order
        $order = new Order(); $order->save();
        $order->items()->saveMany($order_items); $order->refresh();
        return $this->respondWithSuccess(code:201, data: ['order' => $order->items ]);
    }

    /**
     * extract array containing []product_ids, assoc [product_id => qty], []OrderItems
     * @param array products
     * 
     * @example => $product_ids, $product_and_qty, $order_items
     * @return array
     */
    protected function preProcessProducts(array $products): array
    {
        $product_ids = [];
        $order_items = [];
        $product_and_qty = [];

        foreach($products as $product){
            $product_ids[] = $product['product_id'];
            $product_and_qty[ $product['product_id'] ] = $product['quantity'];
            //Add OrderItem to the order if code gets here: meaning Exception did not get thrown
            $order_items[] = new OrderItem($product);
        }
        return [ $product_ids, $product_and_qty, $order_items ];
    }

    /**
     * update stock of ingredient
     * 
     * @param Ingredient $ingredient
     * @param ProductIngredient $product_ingredient
     * @param int $qty
     * 
     * @return void
     */
    protected function updateIngredientStock(Ingredient $ingredient, ProductIngredient $product_ingredient, int $qty)
    {
        //reduce the available quantity
        $ingredient->available_qty -= $product_ingredient->quantity * $qty;

        //check if stock only just went down to or below 50% 
        if (
            $ingredient->needs_restock == "false" &&
            isLessOrEqualToThreshold($ingredient->initial_qty, $ingredient->available_qty, $ingredient->threshold) 
        ){
            //set to true, so future updates don't trigger dispatch, except new stock has been added 
            $ingredient->needs_restock = 'true';
            $ingredient->save();
        }else{
            $ingredient->saveQuietly();
        }
    }

}