<?php

class AdController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new AdModel();
    }

    public function admin_index()
    {
        $this->data['ad'] = $this->model->getList();
    }

    public function admin_add(){
        if(!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['href']) &&
                !empty($_POST['company']) && !empty($_POST['telephone']))
        {
                $result = $this->model->save($_POST, $_FILES);
                if($result){
                    Session::setFlash('Page was saved.');
                } else{
                    Session::setFlash('Error.');
                }
                Router::redirect('/admin/ad');
        } else {

        }
    }

    public function admin_edit(){

        $rand = rand(1,1000);

        if(!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['href']) &&
            !empty($_POST['company']) && !empty($_POST['telephone']))
        {
            $id = $_POST['id'];

            $result = $this->model->save($_POST, $_FILES, $id);
            if($result){
                Session::setFlash('Ad was saved.');
            } else{
                Session::setFlash('Error.');
            }

            Router::redirect("/admin/ad?{$rand}");
        }

        if (isset($this->params[0])){
            $this->data['ad'] = $this->model->getById($this->params[0]);
            if(!$this->data['ad']){
                Session::setFlash('Wrong add id.');
                Router::redirect("/admin/ad?{$rand}");
            }
        }else{
            Session::setFlash('Wrong ad id.');
            Router::redirect("/admin/ad?{$rand}");
        }
    }
    
    public function admin_delete(){
        
        if(isset($this->params[0])){

            $result = $this->model->delete($this->params[0]);
            if($result){
                Session::setFlash('Ad was deleted.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/ad');
        }
        
    }

    public function counter(){

        if(!empty($this->params[0])){
            $result = $this->model->counter($this->params[0]);
        }
        exit();
    }
    

}