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
            $data= $this->request->getPost();
            // getting Settings data 
            $Setting = new Settings();
            $Setting=Settings::find(['set_id'=>'1']);

            //Fire event to check Setting 
            $eventsManager = $this->eventsManager;
            $data= $this->eventsManager->fire('notifications:beforeSend', $this,$Setting[0]);
            // Fire event End 

            $product = new Products();
            $product->assign(
                $data,
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