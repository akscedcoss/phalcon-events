<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
      
        
        // return '<h1>Hello World!</h1>';
    }
    public function SettingsAction()
    {
    //   fetch settings frm db 
    // send it to view 
    // update the exsisting id of seeting;
    $Setting = new Settings();
    $Setting=Settings::find();
    $this->view->setting = $Setting[0];
    
    if (true === $this->request->isPost()) { 
        $Setting = new Settings();
        $Setting=Settings::find(['set_id'=>'1']);
        // var_dump($Setting[0]);
        $Setting[0]->assign(
            $this->request->getPost(),
            [
                'title_optimization',
                'default_price',
                'default_stock',
                'default_zipcode',
              
            ]
        );
        $success =  $Setting[0]->save();
        $this->view->success = $success;
        if($success)
        {
            $this->view->message = "Updated  succesfully";

        }
        else {
            $this->view->message = "Updation Failed :<br>" . implode("<br>", $Setting[0]->getMessages());
        }
    }
    }

 

}