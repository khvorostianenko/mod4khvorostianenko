<?php

class BackgroundController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new BackgroundModel();
    }

    public function admin_index(){
        $this->data['style'] = $this->model->getList();
        
    }

    public function admin_add(){
        if(!empty($_POST['id']) && !empty($_POST['color'])){
            $result = $this->model->save($_POST);
            if($result){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/background');
        } else {

        }
    }

}