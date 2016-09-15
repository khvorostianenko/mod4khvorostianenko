<?php

class NewsModel extends Model
{
    public function getNewsByCategory($id){
        $sql = "SELECT n.*, c.name as cat_name FROM news n JOIN categories c ON n.id_categories = c.id  WHERE n.id_categories = {$id}";
        return $this->db->query($sql);
    }

    public function getList($only_published = false){
        $sql = "select * from news";
        if($only_published){
            $sql.= " and is_published = 1";
        }
        return $this->db->query($sql);
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
        return isset($result[0]) ? $result[0] : null;
    }
    
    public function getByTag($tag){
        $sql = "select * from news where tags LIKE '%{$tag}%'" ;
        $result = $this->db->query($sql);
        return isset($result) ? $result : null;
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