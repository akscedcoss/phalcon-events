<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;


class SecureController extends Controller
{
    public function indexAction()
    {
        
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
        $this->response->redirect('/');
    }
    /**
     * addRolesAction function
     * This Function Will Add Roles to DB table and 
     * to Acl Cache File.
     * @return void
     */
    public function addRolesAction()
    {
        $roles = new Roles();
        $roles=Roles::find();
        $this->view->roles=$roles;
        // Process the form to Add Role To Database And Acl File  
        if (true === $this->request->isPost()) { 
            // Add Role to Db 
            $roles = new Roles();       
            $roles->assign(
                $this->request->getPost(),
                [
                    'role',                  
                ]
            );
            $success =  $roles->save();
            //  check if Acl file Exists
            // If ACl File Exists -> unseralize it and add Role to is  
            $aclfile = APP_PATH . '/security/acl.cache';
            if (true !== is_File($aclfile)) {
                // echo "File Not exists";
                // Redirect to build ACl And Role will be added as its in DB.
                $this->response->redirect('/Secure/buildacl');
            }
            else {
                // echo " File Exists";
                //Step 1 Unserialize
                $acl = unserialize(file_get_contents($aclfile));

                //Step 2 Add Role in ACL File  
                 $acl->addRole( $this->request->getPost()['role']);
                //  Step 3 Serialize File Again
                 file_put_contents(
                    $aclfile,
                    serialize($acl)
                );
                //  Step 4 Redirect To same Add Roles page
                $this->response->redirect('/Secure/addRoles');
            }
        }
    }
    

    public function addComponentsAndactionAction()
    {


    }
   
}