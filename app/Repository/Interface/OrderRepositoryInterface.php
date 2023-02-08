<?php
namespace App\Repository\Interface;

use App\Http\Requests\OrderRequest;

interface OrderRepositoryInterface{

  public function create(OrderRequest $request); #would require Request Handler
  
}