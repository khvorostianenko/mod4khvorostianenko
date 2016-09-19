<?php
class BarsController extends Controller{
    private $data_from_db;
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new BarsModel();
    }

    public function left_bar(){
        $this->data['top5_users'] = $this->model->get_5top_users();
        $this->data['top3_news'] = $this->model->get_3top_news();
        $this->data['search_tags'] = $this->model->get_tags();
        $this->data['categories'] = $this->model->get_categories();
    }

    public function left_ad(){
        $this->data['ad'] = $this->model->getAdList();
        $this->data_from_db = $this->data['ad'];
        $this->data['left_side_flag'] = true;
    }

    public function right_ad(){
        $this->data['ad'] = $this->data_from_db;
        $this->data['left_side_flag'] = false;
    }

    public function style(){
        $this->data['style'] = $this->model->getStyle();
    }
    
    public function nav_bar(){
        $this->data['nav'] = $this->model->getNavList();
        $this->data['nav_data'] = $this->data['nav'];
        $this->data['nav'] = $this->show_menu();
    }

    public function isChildren($parent){
        foreach($this->data['nav'] as $row){
            if($row['parent_id'] == $parent)
                return true;
        }
        return false;
    }

    public function show_menu($parent = 0){
        $text = '';
        if($parent == 0){
            $text = '<ul class="main-menu nav nav-pills">';
        } else {
            $text.= '<ul class="main-menu">';
        }

        foreach($this->data['nav'] as $row){
            if($row['parent_id'] == $parent)
            {
                if(($row['id'] == 6) && Session::get('role') == 'user'){

                } else {
                    $text.="<li>
                            <a href='{$row['href']}'>{$row['name']}</a>";

                    $new_parent = $row['id'];
                    if($this->isChildren($new_parent)){
                        $text.= $this->show_menu($new_parent);
                    }
                    $text.="</li>";
                }
            }
        }
        return $text.'</ul>';
    }
}