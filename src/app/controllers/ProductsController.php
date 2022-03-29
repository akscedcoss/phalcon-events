<?php

use Phalcon\Mvc\Controller;


class ProductsController extends Controller
{
    public function indexAction()
    {
        $products = new Products();
        $allProds=Products::find();
        $this->view->allProds=$allProds;
    }
    public function addAction()
    {
        echo "i am here to add new products";
    }


}