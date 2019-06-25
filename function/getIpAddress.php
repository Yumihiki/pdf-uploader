<?php

function getIpAddress() 
{
  if(!is_null($_SERVER['REMOTE_ADDR'])) 
  {
    return $_SERVER['REMOTE_ADDR'];
  }
}

?> 