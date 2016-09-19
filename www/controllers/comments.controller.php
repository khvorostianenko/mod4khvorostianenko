<?php

class CommentsController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new CommentsModel();
    }

    public function admin_index(){
        $this->data['page'] = $this->model->getList();
    }

    public function admin_check(){
        $this->data['page'] = $this->model->getCheckList();
    }
    
    public function admin_add(){

    }

    public function admin_publish(){
        if (isset($this->params[0])){
            $this->data['comment'] = $this->model->publish($this->params[0]);
            if(!$this->data['comment']){
                Session::setFlash('Wrong news id.');
                Router::redirect('/admin/comments/check');
            }
        }else{
            Session::setFlash('Wrong news id.');
            Router::redirect('/admin/comments/check');
        }
        Router::redirect('/admin/comments/check');
    }

    public function admin_edit(){
        
        
        if(!empty($_POST['text']) && !empty($_POST['id_news']) && !empty($_POST['id_user'])){
            $id = $_POST['id'];
            $result = $this->model->save($_POST, $id);
            if($result){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/comments');
        }

        if (isset($this->params[0])){
            $this->data['comment'] = $this->model->getById($this->params[0]);
            if(!$this->data['comment']){
                Session::setFlash('Wrong news id.');
                Router::redirect('/admin/comments');
            }
        }else{
            Session::setFlash('Wrong news id.');
            Router::redirect('/admin/comments');
        }
    }

   
    
    
    public function add(){
        if(!empty($_POST['text']) && !empty($_POST['id_news']) && !empty($_POST['id_user'])){
            $result = $this->model->save($_POST);
            if($result[0]){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/news/item/'.$_POST['id_news'].'/'.$result[1]);
        } elseif(!empty($_POST['id']) && !empty($_POST['text'])){
            
            $result = $this->model->user_save($_POST['text'], $_POST['id']);
            if($result[0]){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/news/item/'.$this->params[0].'/'.$this->params[1]);
        }

    }

    public function likes(){
        echo $this->model->likes($this->params[0], $this->params[1]);
        exit;
    }

    public function addform(){
        $this->data['page']['id'] = $this->params[0];
        $this->data['page']['parent_id'] = $this->params[1];
    }

    public function users(){
        if(!empty($this->params[2]))
        {
            $this->data['pagination'] = $this->params[2];
        } else {
            $this->data['pagination'] = '';
        }

        $this->data['count_for_paginatior'] = floor($this->model->getCount($this->params[0])/5) + 1;
        $this->data['user'] = $this->model->user($this->params[0], $this->data['pagination']);
        $this->data['nick'] = $this->params[1];
        $this->data['user_id'] = $this->params[0];
    }

    public function admin_reply(){

        if(!empty($_POST['text']) && !empty($_POST['id_news']) && !empty($_POST['id_user'])){
            $result = $this->model->save($_POST);
            if($result){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/comments');
        }

        $this->data['comments'] = $this->model->getById($this->params[0]);
        $this->data['comments'][0]['user'] = $this->params[1];

    }

    public function admin_delete(){
        if(isset($this->params[0])){
            $result = $this->model->delete($this->params[0]);
            if($result){
                Session::setFlash('Comment was deleted.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/comments');
        }
    }
}