<?php

class UsersController extends  Controller{
    public  function  __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new  User();

    }

    public function account_login(){
        if ($this->params[0] == 'singup'){
            Session::setFlash('регистрация прошла успешно, теперь Вы можете войти');
        }
   if( $_POST && isset($_POST['email']) && isset($_POST['pass'])){
       $user = $this->model->getByEmail($_POST['email']);
       $hash= md5(Config::get('salt').$_POST['pass']);
       if($user  && $hash == $user['pass'] ){
           Session::set('email', $user['email']);
           Session::set('role', $user['role']);
           Session::set('id_user', $user['id_user']);
           Router::redirect('/news');
       } else { Session::setFlash('ошибка');}

   }
    }

    public function admin_login(){

        if( $_POST && isset($_POST['email']) && isset($_POST['pass'])){
            $user = $this->model->getByEmail($_POST['email']);
            $hash= md5(Config::get('salt').$_POST['pass']);
          //  print_r($_SESSION);
            if($user  && $hash == $user['pass'] ){
                Session::set('email', $user['email']);
                Session::set('role', $user['role']);
                Session::set('id_user', $user['id_user']);
                Router::redirect('/admin');
            } else { Session::setFlash('ошибка');}

        }
    }

    public function  account_singup(){
     if ($_POST['pass'] == $_POST['pass1']){
        if ($_POST) {
            $hash = md5(Config::get('salt') . $_POST['pass']);
            $verifEmail = $this->model->getByEmail($_POST['email']);
            if ($verifEmail['email'] == $_POST['email']) {
                Session::setFlash('учетная запись с этим email уже занята');
            } else {
                if ($this->model->singUp($_POST, $hash)) {


                    Router::redirect('/account/users/login/singup');



                } else {
                    Session::setFlash('Ошибка ');
                }
            }


        }
    }  else{Session::setFlash('Пароли не совпадают');}
    }




    public function account_logout(){
        Session::destroy('/admin');
        Router::redirect('/news/');
    }


    public  function  account_userprofile(){

        $login = Session::get('email');

        $this->data = $this->model->userProfile($login);

        $params = App::getRouter()->getParams();
        print_r($params);
    }
}