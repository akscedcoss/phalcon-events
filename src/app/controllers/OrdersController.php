<?php

use Phalcon\Mvc\Controller;


class OrdersController extends Controller
{
    public function indexAction()
    {
        echo "hi";
        $orders = new Orders();
        $allorders=Orders::find();
        $this->view->allorders=$allorders;

    }
    public function addAction()
    {
        if (true === $this->request->isPost()) {
            echo " i am post request";
            print_r($this->request->getPost());
           
            $order = new Orders();
            $order->assign(
                $this->request->getPost(),
                [
                    'customer_name',
                    'customer_address',
                    'zipcode',
                    'product',
                    'quantity'
                ]
            );
            $success =  $order->save();
            $this->view->success = $success;
            if($success)
            {
                $this->view->message = "Added succesfully";
            }
            else {
                $this->view->message = "Order Not Added reason:<br>" . implode("<br>", $order->getMessages());
            }
            
        }

        // Sending Details of All Products 
        $products = new Products();
        $allProds=Products::find();
        $this->view->allProds=$allProds;
    }


}