<?php
class MenuController extends Controller{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new MenuModel();
    }
    
    public function admin_index(){
        $this->data['menu'] = $this->model->getList();
    }

    public function admin_add(){
        echo '<meta charset="utf-8">';
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        if(!empty($_POST['name'])){
            $result = $this->model->save($_POST);
            if($result){
                Session::setFlash('Menu was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/menu');
        } else {
            Router::redirect('/admin/menu');
        }
    }

    public function admin_delete(){

        if(isset($this->params[0])){

            $result = $this->model->delete($this->params[0]);
            if($result){
                Session::setFlash('Menu was deleted.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/menu');
        }
    }

    public function admin_edit(){
        if((!empty($_POST['name']) && !empty($_POST['id'])))
        {
            $id = $_POST['id'];
            $result = $this->model->save($_POST, $id);
            if($result){
                Session::setFlash('Menu was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/menu');
        }

        if (isset($this->params[0])){
            $this->data['menu'] = $this->model->getById($this->params[0]);
            if(!$this->data['menu']){
                Session::setFlash('Wrong menu id.');
                Router::redirect('/admin/menu/');
            }
        }else{
            Session::setFlash('Wrong menu id.');
            Router::redirect('/admin/menu/');
        }
    }
}