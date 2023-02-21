<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Repository\Interface\OrderRepositoryInterface;

class OrderController extends Controller
{

  public function __construct(protected OrderRepositoryInterface $orderRepo){}

  /**
   * create order and returns appropriate response 
   * 
   * @param OrderRequest $request
   * 
   * @return Illuminate\Http\JsonResponse
   */
  public function createOrder(OrderRequest $request)
  {
    return $this->orderRepo->create($request->producs);
  }

}
