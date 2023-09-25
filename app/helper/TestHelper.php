<?php

/*
    Asatru PHP - example helper
*/

/**
 * Just an example helper function
 * 
 * @param int $max
 * @return int
 */
function MyExampleHelperFunction($max = 1000)
{
    return rand(1, $max);
}