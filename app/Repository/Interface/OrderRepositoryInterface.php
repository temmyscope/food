<?php
namespace App\Repository\Interface;

use App\Http\Requests\OrderRequest;

interface OrderRepositoryInterface{

  public function create(array $products);
  
}