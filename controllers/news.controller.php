<?php

class NewsController extends  Controller{

    public  function  __construct($data= array())
    {
        parent::__construct($data);
        $this->model = new Nevsis();
    }

    public function  index(){

      $category = $this->model->getListCategory();
        $this->data['slaider'] = $this->model->mainSlaider();
        $this->data['topcoment'] = $this->model->topcoment();
        $this->data['topnews'] = $this->model->topnews();
        //print_r($this->data['topnews']);
        $layot = $category;
        foreach($layot as $category) {
            $this->data['news'][$category[alias]]  = $this->model->getCategory($category[alias],5);

        }

    }

    public function  admin_index(){

        $category = $this->model->getListCategory();
        $this->data['slaider'] = $this->model->mainSlaider();

        $layot = $category;
        foreach($layot as $category) {
            $this->data['news'][$category[alias]]  = $this->model->getCategory($category[alias],5);

        }

    }

    public  function  admin_all_category(){
           $this->data['category_list'] = $this->model->getListCategory();

    }

    public function  admin_edit_category()
    {
        $params = App::getRouter()->getParams();
        if (isset($params[0])) {
            $this->data['category'] = $this->model->getCategoreById($params[0]);
            //  print_r($this->data);
            if ($_POST) {
                $this->model->updateCategory($_POST, $params[0]);
                Router::redirect('/admin/news/edit_category/' . $params[0] . '/save');
            }
        } else {
            if ($_POST) {
                $this->model->saveCategory($_POST);
                $max_id = $this->model->maxIdCategory();
                $last_id = $max_id[0][max];


                Router::redirect('/admin/news/edit_category/' . $last_id . '/save');
            }

        }
        if (isset($params[1]) AND $params[1] == 'save') {
            Session::setFlash('категория сохранена');
        }
    }

        public  function admin_all_news(){
        $this->data['news']= $this->model->getListNews();
      //  print_r($this->data);
    }

    public  function admin_edit_news(){
        $params = App::getRouter()->getParams();
        $this->data['catlist'] = $this->model->getListCategory();
        $this->data['tags'] = $this->model->getAllTags();
        if (isset($params[0])) {
            $this->data['news'] = $this->model->getListNewsForEdit($params[0]);
            //  print_r($this->data['news']);



            $this->data['select_tags'] = $this->model->getTags($params[0]);
            // print_r($this->data);
            if ($_POST) {
                $this->model->updateNews($_POST, $params[0]);
                $this->model->updateTags($_POST[tags], $params[0]);
                Router::redirect('/admin/news/edit_news/' . $params[0].'/save');
            }
        }else{
            if ($_POST) {
                $this->model->saveNews($_POST);
                $max_id = $this->model->maxIdNews();
                  $last_id =  $max_id[0][max] ;

               $this->model->updateTags($_POST[tags], $last_id);
                Router::redirect('/admin/news/edit_news/' . $last_id.'/save');
            }

        }
        if (isset($params[1]) AND $params[1]=='save') {
            Session::setFlash('cтатья сохранена');
        }
    }


 public function admin_all_coments(){
     $this->data['coments']= $this->model->comentsAllWithNews();
     //print_r($this->data);
}
    public function  admin_edit_coments() {
        $params = App::getRouter()->getParams();
        if (isset($params[0])) {
            $this->data['coments'] = $this->model->comentsedit($params[0]);
           // print_r($this->data);
            if ($_POST) {
                $this->model->updateComents($_POST, $params[0]);

               Router::redirect('/admin/news/edit_coments/' . $params[0].'/save');
            }
        }
    }


    public function  category()
    {
        $layot = $this->params[0];
        //echo $layot;
        if (!$layot) {
            Router::redirect('http://modul/news/');
        } else {
            $this->data['news'] = $this->model->getCategory($layot);

        }
    }



    public  function  view(){
        $params = App::getRouter()->getParams();
     //   print_r($params[0]);
     //   print_r($params[1]);
     //   print_r($params[2]);
        if (isset($params[0])){
            $id = strtolower($params[0]);
            $this->data['news'] =$this->model->getById($id);
            $this->data['cat_name'] =$this->model->getCategoreById($this->data['news']['id_category']);

            $this->data['tags'] = $this->model->getTags($id);
            $this->data['coments'] = $this->model->getComentsById($id);
            foreach ( $this->data['coments'] as $coment) {
                $this->data['like'][$coment[id_coments]] = $this->model->countLike($coment[id_coments],'plus');
                $this->data['dislike'][$coment[id_coments]] = $this->model->countLike($coment[id_coments],'minus');

             }
            if( $this->data['news'][id_category] == '1'){$is_active = '0';}else{$is_active = '1';}
           // echo $is_active;
            if ($_POST) {
               if( $this->model->saveComents($_POST,$is_active)){

                   Router::redirect("/news/view/$id");
               }

            }
            if (isset($params[1]) AND isset($params[2])){
                $this->model->saveLike($params[1],$params[2]);
               Router::redirect('/news/view/'.$id);
            }
           if (isset($params[1])) {
               cookie::set($params[2], $params[1], $time = 31536000);
              //  print_r(cookie:: coc());
          }
            foreach($this->data['coments'] as $coment){

                $is_cookie =cookie::get($coment[id_coments]);
                if (isset($is_cookie)){$this->data[is_vote][$coment[id_coments]]='true';  }
            }


       //     print_r(cookie:: coc());
       //    print_r($this->data);
        }

    }

    public  function  vievTags(){
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $name1 = strtolower($params[0]);
        } else {
            $name1 = null;
        }
        if (isset($params[1])) {
            $name2 = strtolower($params[1]);
        } else {
            $name2 = null;
        }
        $this->data['tags'] = $this->model->getTagsByName($name1);//получаем список статей по обному тегу
        $this->data['tag_name'][1] = $this->model->getTagByName($name1);//получаем имена тегов
        $this->data['tag_name'][2] = $this->model->getTagByName($name2);
        $name2 =$this->data['tag_name'][2][0][id_tegs];

        foreach ($this->data['tags'] as $item){
            $id_news[] = $item['id_news'];
        }

        if($name2 != null) {
            foreach ($id_news as $item) {
                $this->data['tags_rep'][] = $this->model->getTagByIdNews($item, $name2);
            }
            foreach ($this->data['tags_rep'] as $item) {
                if (!empty($item)) {
                    $t[] = $item;
                }
            }
            $this->data['tags_rep'] = $t;
            unset($this->data['tags']);
            foreach ($this->data['tags_rep'] as $item) {
                $this->data['tags'][] = $this->model->getById($item[0][id_news]);

            }
        }
     //   print_r($this->data);
    }

    public function  sorting(){
        $params = App::getRouter()->getParams();
        if (isset($params[0])){
        $period = explode("-", $params[0]);
        $mouth = $period[0];
        $year = $period[1];}
        else{
            $mouth = null;
            $year = null;
        }




        $this->data['period']=  $params;
        $this->data['news'] = $this->model->sorting( $mouth,$year);
        $this->data['tags'] = $this->model->getAllTags();

    //   print_r($this->data);
    }

 public  function coments(){
       $params = App::getRouter()->getParams();
     if (isset($params[0])){
     //  echo $params[0];
    $this->data['coments'] = $this->model->comentOfUser($params[0]);
       }
     //print_r($this->data);
   //  echo $params[1] .$params[2];
     if (isset($params[1]) AND isset($params[2])){
         $this->model->saveLike($params[1],$params[2]);
         Router::redirect('/news/coments/'.$this->data['coments'][0][id_user]);
     }
     if (isset($params[1])) {
         cookie::set($params[2], $params[1], $time = 31536000);
         //  print_r(cookie:: coc());
     }
     foreach($this->data['coments'] as $coment){

         $is_cookie =cookie::get($coment[id_coments]);
         if (isset($is_cookie)){$this->data[is_vote][$coment[id_coments]]='true';  }
     }
     foreach ( $this->data['coments'] as $coment) {
         $this->data['like'][$coment[id_coments]] = $this->model->countLike($coment[id_coments],'plus');
         $this->data['dislike'][$coment[id_coments]] = $this->model->countLike($coment[id_coments],'minus');

     }
}


}