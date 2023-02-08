<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Repository\Interface\OrderRepositoryInterface;


class OrderController extends Controller
{
  //
  public function __construct(protected OrderRepositoryInterface $orderRepo){}

  public function getMyProfile(OrderRequest $request)
  {
    return $this->orderRepo->create($request);
  }

}
