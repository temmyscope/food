<?php

/**
 * determines if available quantity is 50% or less
 * 
 * @param float initial
 * @param float available
 * 
 * @return bool
 */
function is50PercentOrLess(float $initial, float $available): bool{
  return floatval(($available/$initial)*100) <= 50 ? true : false;
}