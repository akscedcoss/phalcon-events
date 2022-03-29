<?php

namespace App\Components;
use Phalcon\Escaper;
use Phalcon\Mvc\Components;

class myescaper 
{
 public function sanitize($data)
 {
    $escaper = new Escaper();
    return $escaper->escapeHtml($data);
 }
}
