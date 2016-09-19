<?php

class NewsModel extends Model
{
    public function getNewsByCategory($id, $pagination = false){
        $sql = "SELECT n.*, c.name as cat_name FROM news n JOIN categories c ON n.id_categories = c.id  WHERE n.id_categories = {$id}";
        if($pagination){
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        } else {
            $sql.= " LIMIT 5 ";
        }

        return $this->db->query($sql);
    }

    public function getNewsAnalytic($pagination){
        $sql = "SELECT n.*, c.name as cat_name FROM news n JOIN categories c ON n.id_categories = c.id  WHERE n.analitycs = 1";

        if($pagination){
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        } else {
            $sql.= " LIMIT 5 ";
        }
        $result =  $this->db->query($sql);
        $result[0]['cat_name'] = 'Аналитика';
        return $result;
    }


    public function getCountByAnalytic($id){
        $sql = "SELECT COUNT(*) as `count` FROM news n JOIN categories c ON n.id_categories = c.id  WHERE n.analitycs = 1";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }


    public function getCountByCategory($id){
        $sql = "SELECT COUNT(*) as `count` FROM news n JOIN categories c ON n.id_categories = c.id  WHERE n.id_categories = {$id}";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }

    public function getCountByTag($tag){
        $sql = "select COUNT(*) as `count` from news where tags LIKE '%{$tag}%'" ;
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }
    
    public function getCount(){
        $sql = "SELECT COUNT(*) as `count` FROM news";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }

    public function getCountWidesearch(){
        $sql = "SELECT COUNT(*) as `count` FROM news WHERE is_published = 1";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }


    public function getByTag($tag, $pagination = null){
        $sql = "select * from news where tags LIKE '%{$tag}%'" ;
        if($pagination){
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        } else {
            $sql.= " LIMIT 5 ";
        }
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
    }

    

    public function getById($id)
    {
        $id=(int)$id;
        $sql = "select * from news where id = '{$id}' limit 1" ;
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getByIdWithExplodeTags($id)
    {
        $id=(int)$id;
        $sql = "select * from news where id = '{$id}' limit 1" ;
        $result = $this->db->query($sql);

        $tags = explode(',',$result[0]['tags']);
        for($i = 0; $i<count($tags); $i++){
            $result[0]['tags_exp'][$i] = trim($tags[$i]);
        }

        if(!Session::get('login') && $result[0]['analitycs'] == true) {
            $strings = explode('. ', $result[0]['content']);
            $str = '';

            if (count($strings) > 5) {
                for ($i = 0; $i < 5; $i++) {

                    $str.= trim($strings[$i]).'.';
                }
                $result[0]['content'] = $str;
            }

        }
        return isset($result[0]) ? $result[0] : null;
    }
    
    

    public function save($data, $id=null){

        $id = (int)$id;
        $id_categories = $this->db->escape(Validate::fixString($data['id_categories']));
//        $date = $this->db->escape(Validate::fixString($data['date']));
        $title = $this->db->escape(Validate::fixString($data['title']));
        $content = $this->db->escape($data['content']);
        $show_counter = $this->db->escape(Validate::fixString($data['show_counter']));
        $tags = $this->db->escape(Validate::fixString($data['tags']));
        $analitycs = isset($data['analitycs']) ? 1 : 0;
        $is_published = isset($data['is_published']) ? 1 : 0;

        if(!$id){ // Add new record
            $sql = "
            INSERT INTO news
                SET id_categories = '{$id_categories}',
                    date = NOW(),
                    title = '{$title}',
                    content = '{$content}',
                    show_counter = '{$show_counter}',
                    tags = '{$tags}',
                    analitycs = '{$analitycs}',
                    is_published = '{$is_published}'
            ";
        } else {// Update existing record
            $sql = "
            UPDATE news
                SET id_categories = '{$id_categories}',
                    date = NOW(),
                    title = '{$title}',
                    content = '{$content}',
                    show_counter = '{$show_counter}',
                    tags = '{$tags}',
                    analitycs = '{$analitycs}',
                    is_published = '{$is_published}'
                WHERE id = {$id}
            ";
        }

        return $this->db->query($sql);
    }
    
    public function delete($id){
        $id = (int)$id;
        $sql = "delete from news where id = {$id}";
        return $this->db->query($sql);
    }

    public function save_count($id){
        $sql = "SELECT show_counter FROM news WHERE id = {$id}";
        $old_count = $this->db->query($sql);
        $now_reading = rand(1,3);
        $new_show_counter = $old_count[0]['show_counter'] + $now_reading;
//        var_dump($new_show_counter);
        $sql = "UPDATE news SET show_counter = '{$new_show_counter}' WHERE id = {$id}";
        $this->db->query($sql);
        $result[0] = $new_show_counter;
        $result[1] = $now_reading;
        echo $new_show_counter;
        echo '<br>';
        echo 'Сейчас читают:';
        echo $now_reading;
        echo '<br>';
        return $result;
    }

    public function getList($pagination = false, $only_published = false){
        $sql = "select * from news";
        if(!$pagination)
        {
            if($only_published){
                $sql.= " and is_published = 1";
            }
            $sql.= " ORDER BY date DESC ";
            $sql.= " LIMIT 5 ";
        } else {
            if($only_published){
                $sql.= " and is_published = 1";
            }
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        }
        return $this->db->query($sql);
    }
    
    public function wide_search($data, $pagination = false)
    {
        $sql = "SELECT * FROM news WHERE is_published = 1";
        $message = '';
        foreach($data as $key => $value){
            if($key == 'date'){
                $sql.= " AND date > (NOW() - INTERVAL 60*{$value} MINUTE) ";
                $message.= "Период: ";
                switch($value){
                    case '1':
                        $message.= 'за последний час<br>';
                        break;
                    case '24':
                        $message.= 'за сутки<br>';
                        break;
                    case '24*7':
                        $message.= 'за неделю<br>';
                        break;
                }
            }
            if($key == 'tags'){
                $message.= 'Теги: ';
                if(is_array($value)){
                    for($i = 0; $i < count($value); $i++){
                        $sql.= " AND {$key} LIKE '%{$value[$i]}%' ";
                        $message.= " $value[$i] ";

                    }
                } else {
                    $sql.= " AND {$key} LIKE '%{$value}%' ";
                    $message.= " $value ";
                }
            }
            if($key == 'id_categories'){
                $message.= '<br>Категории: ';
                if(is_array($value)){
                    for($i = 0; $i < count($value); $i++){
                        if($value[$i] == 5){
                            $sql.= " AND analitycs = '1' ";
                            $message.= ' аналитика ';
                        } else {
                            $sql.= " AND {$key} = '{$value[$i]}' ";
                            $cat_sql = "SELECT name FROM categories WHERE id = {$value[$i]}";
                            $cat_result = $this->db->query($cat_sql);
                            $message.= " {$cat_result[0]['name']} ";
                        }
                    } 
                } else {
                    if($value == 5){
                        $sql.= " AND analitycs = '1' ";
                        $message.= ' аналитика ';
                    } else {
                        $sql.= " AND {$key} = '{$value}' ";
                        $cat_sql = "SELECT name FROM categories WHERE id = {$value}";
                        $cat_result = $this->db->query($cat_sql);
                        $message.= " {$cat_result[0]['name']} ";
                    }
                }
            }

        }

        if($pagination)
        {
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        }
        
        $result = $this->db->query($sql);
        $result[0]['message'] = $message;
        return $result;

    }

    public function search_tags_by_tag($tag){
        $sql = "SELECT tags FROM news WHERE tags LIKE '%{$tag}%'" ;
        $result = $this->db->query($sql);
        if($result){
            for($i = 0; $i <count($result); $i++){
                $tags = explode(',',$result[$i]['tags']);
                for($j = 0; $j<count($tags); $j++){
                    $trim_tag = trim($tags[$j]);
//                    var_dump($trim_tag);
                    if(preg_match("/^{$tag}.*/ui", $trim_tag)){
                        $tags_serched[] =  $trim_tag;
                    }
                }
            }
            if(!empty($tags_serched)){
                $tags_serched = array_unique($tags_serched);
                $string = '';
                foreach($tags_serched as $value){
                    $string.= "<option>$value</option>";
                }

            }
        }
        return isset($string) ? $string : null;
    }
}