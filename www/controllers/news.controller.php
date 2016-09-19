<?php

class NewsController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new NewsModel();
    }

    public function widesearch(){

        if(!empty($this->params[0]))
        {
            $this->data['pagination'] = $this->params[0];
        } else {
            $this->data['pagination'] = '';
        }

        $this->data['widesearch'] = $this->model->wide_search($_POST, $this->data['pagination']);
        $this->data['count_for_paginatior'] = floor($this->model->getCountWidesearch()/5) + 1;
    }

    public function admin_index()
    {
        if(!empty($this->params[0]))
        {
            $this->data['pagination'] = $this->params[0];
        } else {
            $this->data['pagination'] = '';
        }

        $this->data['page'] = $this->model->getList($this->data['pagination']);
        $this->data['count_for_paginatior'] = floor($this->model->getCount()/5) + 1;
        $category_object = new CategoriesModel;
        $this->data['categories'] = $category_object->getList();

    }

    public function tag(){

        if(!empty($this->params[1]))
        {
            $this->data['pagination'] = $this->params[1];
        } else {
            $this->data['pagination'] = '';
        }
        
        $this->data['tag'] = '';
        $this->data['page'] = '';
        //var_dump($_POST);
        if(!empty($_POST['tag'])){
            $this->data['page'] = $this->model->getByTag($_POST['tag'], $this->data['pagination']);;
            $this->data['tag'] = $_POST['tag'];
        }
        if(isset($this->params[0]) && $this->params[0] != ''){
            $this->data['page'] = $this->model->getByTag($this->params[0], $this->data['pagination']);
            $this->data['tag'] = $this->params[0];
        }
        $this->data['count_for_paginatior'] = floor($this->model->getCountByTag($this->data['tag'])/5) + 1;
    }
    
    public function categories(){
        $this->data['categories_id'] = $this->params[0];
        if(!empty($this->params[1]))
        {
            $this->data['pagination'] = $this->params[1];
        } else {
            $this->data['pagination'] = '';
        }

        if($this->params[0] != 5){
            $this->data['page'] = $this->model->getNewsByCategory($this->params[0], $this->data['pagination']);
            $this->data['count_for_paginatior'] = floor($this->model->getCountByCategory($this->params[0])/5) + 1;
        } else {
            $this->data['page'] = $this->model->getNewsAnalytic($this->data['pagination']);
            $this->data['count_for_paginatior'] = floor($this->model->getCountByAnalytic($this->params[0])/5) + 1;
        }
    }

    public function item(){
        if(!empty($this->params[1])){
            $lastId = $this->params[1];
        } else {
            $lastId = '';
        }
        $this->data['page'] = $this->model->getByIdWithExplodeTags($this->params[0]);
        $comments_model = new CommentsModel();
        $this->data['comments'] = $comments_model->getListById($this->params[0]);
        $this->data['comments_data'] = $this->data['comments'];
        $this->data['comments'] = $this->show_comments(0, $lastId);
    }

    public function isChildren($parent){
        foreach($this->data['comments'] as $row){
            if($row['parent_id'] == $parent)
                return true;
        }
        return false;
    }

    public function show_comments($parent = 0, $lastId = ''){
        $text = '<ul>';
        foreach($this->data['comments'] as $row){




            if($row['parent_id'] == $parent){

                if($row['id'] == $lastId){


                    $text.='<li style="display: block" id="last_comment_edit" class="comment_block"><div class="comment_info">'.$row['date'].' '.
                        preg_replace('/@.*/', '', $row['email']).':
                    </div>';
                    $text.="<div class='comment_text'>
                                    
                                    <form method='post' action='/comments/add/{$row['id_news']}/{$row['id']}' id='last_comment'>";
                                        $text.='<input name="text" type="text" form="last_comment" class="form-control"  value="'.$row['text'].'">';
                                        $text.='<input name="id" hidden type="text" form="last_comment" value="'.$row['id'].'">';
                                        $text.="<label style='font-size: 12px'>В течении минуты Вы можете перезаписать комментарий</label>";
//                                        $text.= '<button type="submit" class="btn btn-xs btn-default" form="last_comment">Перезаписать ответ</button>
                                        $text.= '<input type="submit" class="btn btn-xs btn-default" form="last_comment" value="Перезаписать ответ">
                                    </form>';
                    $text.='</div></li>';




                    $text.='<li style="display: none;" id="last_comment_show" class="comment_block"><div class="comment_info">'.$row['date'].' '.
                        preg_replace('/@.*/', '', $row['email']).':
                    </div>
                    <div class="comment_text">'.$row['text'].'</div>';
                    $text.='<div>
                            <button onClick="forNewDom(\'/comments/likes/'.$row['id'].'/1\', loyout_flag = 0, \'likes'.$row['id'].'\')" class="btn btn-xs btn-success">
                    <b>+</b></button>'.' '.'<span id="likes'.$row['id'].'">'.$row['likes'].'</span> '.'
                            <button onClick="forNewDom(\'/comments/likes/'.$row['id'].'/0\', loyout_flag = 0, \'likes'.$row['id'].'\')" 
                    class="btn btn-xs btn-warning">-</button>';

                    if(Session::get('login')){
                        $text.= ' <button onClick="forNewDom(\'/comments/addform/'.$row['id_news'].'/'.$row['id'].'\', \'1\', \'likesform'.$row['id'].'\')" 
                    class="btn btn-xs btn-info">Ответить</button>
                    <span id="likesform'.$row['id'].'"></span>';
                    }

                    $text.='</div></li>';

                } 
                    else
                {

                $text.='<li class="comment_block"><div class="comment_info">'.$row['date'].' '.
                    preg_replace('/@.*/', '', $row['email']).':
                    </div>
                    <div class="comment_text">'.$row['text'].'</div>';
                $text.='<div>
                            <button onClick="forNewDom(\'/comments/likes/'.$row['id'].'/1\', loyout_flag = 0, \'likes'.$row['id'].'\')" class="btn btn-xs btn-success">
                    <b>+</b></button>'.' '.'<span id="likes'.$row['id'].'">'.$row['likes'].'</span> '.'
                            <button onClick="forNewDom(\'/comments/likes/'.$row['id'].'/0\', loyout_flag = 0, \'likes'.$row['id'].'\')" 
                    class="btn btn-xs btn-warning">-</button>';

                if(Session::get('login')){
                    $text.= ' <button onClick="forNewDom(\'/comments/addform/'.$row['id_news'].'/'.$row['id'].'\', \'1\', \'likesform'.$row['id'].'\')" 
                    class="btn btn-xs btn-info">Ответить</button>
                    <span id="likesform'.$row['id'].'"></span>';
                }

                $text.='</div></li>';
                }



                $new_parent = $row['id'];
                if($this->isChildren($new_parent)){
                    $text.= '<ul>'.$this->show_comments($new_parent, $lastId).'</ul>';
                }
            }
        }
        return $text.'</ul>';
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