<?php

namespace App\Helpers;

function is50PercentOrLess(float $initial, float $available): bool{
  return (($available/$initial)*100) <= 50 ? true : false;
}