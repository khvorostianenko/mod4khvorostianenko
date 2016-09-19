<?php

class SubscribersController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new SubscribersModel();
    }

    public function index(){
        if(!empty($_POST['name']) && !empty($_POST['name'])){

            $result = $this->model->save($_POST);
            if($result){
                $this->data['message'] = "Ваши данные сохранены!!!";
            }             
        } else {
            $this->data['message'] = "Вы не заполнили форму!!!";
        }
        
        
    }

    public function admin_index()
    {
        $this->data['subscribers'] = $this->model->getList();
    }

    public function admin_delete(){

        if(isset($this->params[0])){

            $result = $this->model->delete($this->params[0]);
            if($result){
                Session::setFlash('Subscriber was deleted.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/subscribers');
        }
    }
    
}
