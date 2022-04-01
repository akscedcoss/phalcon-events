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
        $roles = Roles::find();
        $this->view->roles = $roles;
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
            } else {
                // echo " File Exists";
                //Step 1 Unserialize
                $acl = unserialize(file_get_contents($aclfile));

                //Step 2 Add Role in ACL File  
                $acl->addRole($this->request->getPost()['role']);
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
    /**
     * Undocumented function
     *it returns all components An Action
     * @return void
     */
    public function allComponentsAndActio()
    {
        // final Data
        $data = [];
        // Get List of All Controllers 
        $controllers = [];

        foreach (glob(APP_PATH . '/controllers/*Controller.php') as $controller) {
            $className = basename($controller, '.php');
            array_push($controllers, $className);
        }
        // Getting List of 
        foreach ($controllers as $key => $value) {
            $data += [$value => []];

            $ActionMethod = [];
            $className = basename($value, '.php');
            $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                if (\Phalcon\Text::endsWith($method->name, 'Action')) {
                    $ActionMethod[] = $method->name;
                    array_push($data[$value], $method->name);
                }
            }
        }
        // Sending Data Of All Possible Controllers And Actions 
        return $data;
    }

    public function SetPermissionsAction()
    {
        // Get Roles Data From DB and Send To View 
        $roles = new Roles();
        $roles = Roles::find();
        $this->view->roles=$roles;
        // Get All Permisions Details
        $permissons = new Permissions();
        $permissons = Permissions::find();
        $this->view->permissons=$permissons;
        // Get All Controllers And Action
        $data=$this->allComponentsAndActio();

        $this->view->actiondata= array_keys($data);
        $this->view->data=$data;

          
        global  $role;
        // When Roles is Selected 
        if ($this->request->getPost('roles')) {
            // Get Role 
            $role=$this->request->getPost("roles");
            if($role==='@')
            {   $this->view->success=false;
                $this->view->message="Please Select User ";
            }
            else{
                // Now Select Controller 
                $this->view->role=$role;
            }
        }
       // when Controller is Selected 
       if ($this->request->getPost('controller')) { 
         $controller=$this->request->getPost("controller");
         if($controller==='@')
         {   $this->view->success=false;
             $this->view->message="Please Select controller ";
         }
         else{
            $this->view->role=$this->request->getPost("roles");
            $this->view->selectedcontroller=$controller;
        }
       // when Action  is Selected 
       if ($this->request->getPost('actions')) { 
        $controller=$this->request->getPost("actions");
        if($controller==='@')
        {   $this->view->success=false;
            $this->view->message="Please Select actions ";
        }
        else{
            //  print_r($this->request->getPost());
            $role=$this->request->getPost("roles");
            $contr=str_replace("Controller","", $this->request->getPost("controller"));
            $action=str_replace("Action","", $this->request->getPost("actions"));

            // Add Permsions to db;
            $permissons = new Permissions();
            $d=array('role'=>$role,'component'=>$contr,'action'=>$action);
            $permissons->assign(
                $d,
                [
                    'role',
                    'component',
                    'action'

                ]
            );
            $success = $permissons->save();
            echo $success;

           // Add permissions to acl File  ;
           $aclfile = APP_PATH . '/security/acl.cache';

           if (true !== is_File($aclfile)) {
               // Redirect to build ACl And Role will be added as its in DB.
               $this->response->redirect('/Secure/buildacl'); 
             }
            else 
            {
                //Step 1 Unserialize
                $acl = unserialize(file_get_contents($aclfile));
                // Step 2 Add Role 
                $acl->addRole($role);
                // Add Component 
                //  var_dump($acl);
                 echo "++++++++++++++++++++++++++++++++++++++++++++";
                // die();
                $acl->addComponent(
                    $contr,
                    [
                        $action,
    
                    ]
                );
                // var_dump($acl);
                $acl->allow($role, $contr, $action);


            file_put_contents(
                $aclfile,
                serialize($acl)
            );
                echo "++++++++++++++++++++++++++++++++++++++++++++";
        //  Redirect 
        $this->response->redirect('/secure/SetPermissions');
            }

       }
       

       }
    }
}
}
