<?php

namespace Core;

class Controller
{
    public object $view;
    
    public function __construct()
    {
        $this->view = new View();
    }
}