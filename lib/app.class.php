<?php

class App{

    protected static $router;

    public static  $db;
    public  static function getRouter(){
        return self::$router;
    }

    public  function  run($uri){
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'),Config::get('db.user'),Config::get('db.password'),Config::get('db.db_name'));

        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        $layot = self::$router->getRoute();



        if($layot == 'admin' && Session::get('role') !='1' && $controller_method != 'admin_singup' ) {

            if ($controller_method != 'admin_login'  ) {
                Router::redirect('/admin/users/login');
            }
        }






        $controller_object = new $controller_class();

        if (method_exists($controller_object, $controller_method)){
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.'does not exist');
        }


        $layot_path = VIEWS_PATH.DS.$layot.'.html';
        $layot_view_object = new View(compact('content'),$layot_path);
        echo  $layot_view_object->render();
    }
}