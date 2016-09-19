<?php

class PagesController extends Controller{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new PagesModel();
    }
    public function index(){
        $this->data['page'] = $this->model->getList();
        $this->data['slider'] = $this->model->getListForSlider();
    }

    public function view(){
        $params = App::getRouter()->getParams();

        if( isset($params[0])) {
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
        }
    }

    public function user_index(){
//        if(!Session::get('login')){
//            Router::redirect('/signin');
//        }
    }

    public function admin_index(){
        Router::redirect('/admin/categories');
    }



}