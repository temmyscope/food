<?php

/**
 * determines if available quantity is below or less than threshold percentage
 * 
 * @param float initial
 * @param float available
 * @param float threshold percentage
 * 
 * @return bool
 */

function isLessOrEqualToThreshold(float $initial, float $available, float $threshold): bool{
  return floatval(($available/$initial)*100) <= $threshold ? true : false;
}
