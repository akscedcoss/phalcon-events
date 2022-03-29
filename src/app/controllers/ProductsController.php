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
        if (true === $this->request->isPost()) {
            echo " i am post request";
            print_r($this->request->getPost());
            $product = new Products();
            $product->assign(
                $this->request->getPost(),
                [
                    'Name',
                    'Description',
                    'Tags',
                    'Price',
                    'Stock'
                ]
            );
            $success =  $product->save();
            $this->view->success = $success;
            if($success)
            {
                $this->view->message = "Added succesfully";
            }
            else {
                $this->view->message = "Product Not Added reason:<br>" . implode("<br>", $product->getMessages());
            }
            
        }
    }


}