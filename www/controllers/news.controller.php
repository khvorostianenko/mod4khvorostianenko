<?php

class NewsController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new NewsModel();
    }

    public function categories(){
        $this->data['page'] = $this->model->getNewsByCategory($this->params[0]);
    }

    public function item(){
        $this->data['page'] = $this->model->getByIdWithExplodeTags($this->params[0]);
    }

    public function tag(){
        $this->data['tag'] = '';
        $this->data['page'] = '';
        //var_dump($_POST);
        if(!empty($_POST['tag'])){
            $this->data['page'] = $this->model->getByTag($_POST['tag']);;
            $this->data['tag'] = $_POST['tag'];
        }
        if(isset($this->params[0]) && $this->params[0] != ''){
            $this->data['page'] = $this->model->getByTag($this->params[0]);
            $this->data['tag'] = $this->params[0];
        }
    }

    public function admin_index()
    {
        $this->data['page'] = $this->model->getList();
        $category_object = new CategoriesModel;
        $this->data['categories'] = $category_object->getList();
    }

    public function admin_add(){
        if(!empty($_POST['id_categories']) && !empty($_POST['title'])
            && !empty($_POST['content']) && !empty($_POST['tags'])){
            $result = $this->model->save($_POST);
            if($result){
                Session::setFlash('Page was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/news');
        } else {
            $category_object = new CategoriesModel;
            $this->data['page'] = $category_object->getList();
        }
    }
    
    public function admin_edit(){


        if(!empty($_POST['id_categories']) && !empty($_POST['title'])
            && !empty($_POST['content']) && !empty($_POST['tags']))
        {
            $id = $_POST['id'];
            $result = $this->model->save($_POST, $id);
            if($result){
                Session::setFlash('News was saved.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/news');
        }

        if (isset($this->params[0])){
            $this->data['news'] = $this->model->getById($this->params[0]);
            $category_object = new CategoriesModel;
            $this->data['page'] = $category_object->getList();
            if(!$this->data['news']){
                Session::setFlash('Wrong news id.');
                Router::redirect('/admin/news/');
            }
        }else{
            Session::setFlash('Wrong news id.');
            Router::redirect('/admin/news/');
        }
    }

    public function admin_delete(){
        if(isset($this->params[0])){
            $result = $this->model->delete($this->params[0]);
            if($result){
                Session::setFlash('News was deleted.');
            } else{
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/news');
        }
    }

    public function counter(){
        $this->data['page'] = $this->model->save_count($this->params[0]);
        exit();
    }

    public function search(){
        if(!empty($this->params[0])){
            //var_dump($this->params[0]);
            $this->data['page'] = $this->model->search_tags_by_tag($this->params[0]);
            echo $this->data['page'];
        }
        exit();
    }
}