<?php

namespace App\Listener;
use Phalcon\Events\Event;

class NotificationsListener 
{
   public function beforeHandleRequest(Event $event,$application)
   {
 
     $aclfile = APP_PATH . '/security/acl.cache';
     if (is_file($aclfile) == true) {
    
         $acl = unserialize(
             file_get_contents($aclfile)
         );
        //  $role = $application->request->get('role');
        $role='Customer1';
        $req=explode("/",$_SERVER['REQUEST_URI']);
 
        // var_dump($acl);
        $controller = ucwords($req[1])??'index';
        $action = $req[2]??'index';
        echo $controller;
        echo $action;
        //  die;
         if (!$role || true !== $acl->isAllowed($role,   $controller, $action )) {
             echo "access denied";
             echo "<pre>";
             print_r($acl);
             echo "</pre>";
             die();
         }
     } else {

         echo "No ACL ";
        //  die();
     }
  
   }

   








    public function beforeSend (Event $event, $component,$setting)
    {   
        $data=[
                    'Name'=>$component->request->getPost('Name'),
                    'Description'=>$component->request->getPost('Description'),
                    'Tags'=>$component->request->getPost('Tags'),
                    'Price'=>$component->request->getPost('Price'),
                    'Stock'=>$component->request->getPost('Stock')
        ];
        // print_r($component->request->getPost('Name'));

        // echo($setting->title_optimization);
        // with tags.. witout tags
        if($setting->title_optimization=='With tags')
        {
            //Marge name and tags
            $data['Name']=$data['Name'].' '.$data['Tags'];

        } 
        // if price is empty
        if(empty( $data['Price']))
        {
            $data['Price']=$setting->default_price;
        }
        // if stock i empty 
        if(empty( $data['Stock']))
        {
            $data['Stock']=$setting->default_stock;
        }

        return $data;
    }
}