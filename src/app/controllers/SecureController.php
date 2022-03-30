<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;


class SecureController extends Controller
{
    public function indexAction()
    {
        
       echo "ii";
    }
    public function buildaclAction()
    {
        $aclfile = APP_PATH . '/security/acl.cache';
     
        if (true !== is_File($aclfile)) {
            
            $acl = new Memory();
            $acl->addRole('Admin');
            $acl->addRole('Customer');
            $acl->addRole('Guest');
            
            $acl->addComponent(
                'Index',
                [
                    'index',
        
                ]
            );
                        
            $acl->addComponent(
                'Secure',
                [
                    'buildacl',
        
                ]
            );
            
            
            $acl->allow('Admin', 'Index', 'index');
            $acl->allow('Admin', 'Secure', 'buildacl');
            // $acl->deny('Guest', '*', '*');
        
            // $acl->allow('Guest', 'index');
            // $acl->allow('Admin');
          
            file_put_contents(
                $aclfile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclfile)
            
            );
        }
    }
    
   
}