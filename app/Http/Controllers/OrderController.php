<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repository\Interface\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
  public function __construct(protected OrderRepositoryInterface $orderRepo){}

  public function getMyProfile(OrderRequest $request)
  {
    return $this->orderRepo->create($request);
  }

}
