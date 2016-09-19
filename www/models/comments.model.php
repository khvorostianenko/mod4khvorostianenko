<?php

class CommentsModel extends Model{


    public function getList($only_published = false){
        $sql = "select c.*, u.email from comments c JOIN users u ON c.id_user = u.id ORDER BY date DESC";
        if($only_published){
            $sql.= " WHERE is_published = 1";
        }
        return $this->db->query($sql);
    }

    public function getCheckList(){
        $sql = "select c.*, u.email from comments c JOIN users u ON c.id_user = u.id WHERE c.is_published = 0";
        return $this->db->query($sql);
    }

    public function getListById($id){
        $sql = "select c.*, u.email from comments c JOIN users u ON c.id_user = u.id WHERE id_news = '{$id}' AND is_published = 1 ORDER BY parent_id, likes DESC";
        return $this->db->query($sql);
    }

    public function getById($id){
        $sql = "select * from comments WHERE id = '{$id}'";
        return $this->db->query($sql);
    }


    public function user_save($text, $id){

        $id = (int)$id;
        $text = $this->db->escape(Validate::fixString($text));
       
            $sql = "
            UPDATE comments
               SET  text = '{$text}'
                WHERE id = {$id}
            ";
        
        $result_1 = $this->db->query($sql);
        $result_2 = $this->db->getLastId();
        $result[0] = $result_1;
        $result[1] = $result_2;
        return $result;
    }
    
    
    public function save($data, $id=null){

        $id = (int)$id;
        $id_news = $this->db->escape(Validate::fixString($data['id_news']));
        $id_user = $this->db->escape(Validate::fixString($data['id_user']));
        $text = $this->db->escape(Validate::fixString($data['text']));
        $parent_id = $this->db->escape(Validate::fixString($data['parent_id']));

        if(!isset($data['is_published']) && $id == null){
            $sql = "SELECT id_categories FROM news WHERE id = {$id_news}";
            $result = $this->db->query($sql);
            if($result[0]['id_categories'] == 3){
                $is_published = 0;
            } else {
                $is_published = 1;
            }
        } elseif(isset($data['is_published']) && $id != null) {
            $is_published = 1;
        } elseif(!isset($data['is_published']) && $id != null) {
            $is_published = 0;
        }

            
        if(!empty($data['likes'])){
            $likes = $data['likes'];
        } else {
            $likes = 0;
        }

        if(!$id){ // Add new record
            $sql = "
            INSERT INTO comments
                SET id_news = '{$id_news}',
                    id_user = '{$id_user}',
                    likes = '{$likes}',
                    text = '{$text}',
                    date = NOW(),
                    is_published = '{$is_published}',
                    parent_id = '{$parent_id}'
            ";
        } else {// Update existing record
            $sql = "
            UPDATE comments
               SET  likes = '{$likes}',
                    text = '{$text}',
                    is_published = '{$is_published}'
                WHERE id = {$id}
            ";
        }
        $result_1 = $this->db->query($sql);
        $result_2 = $this->db->getLastId();
        $result[0] = $result_1;
        $result[1] = $result_2;
        return $result;
    }
    
    public function likes($id, $like){
        $sql = "SELECT likes FROM comments WHERE id = {$id}";
        $result = $this->db->query($sql);
        $old_likes = $result[0]['likes'];
        if($like){
            $new_like = $old_likes + 1;
        } else {
            $new_like = $old_likes - 1;
        }
        $sql = "UPDATE comments SET  likes = {$new_like} WHERE id = {$id}";
        $result = $this->db->query($sql);
        return $new_like;
    }

    public function getCount($id){
        $sql = "SELECT COUNT(*) as `count` FROM comments WHERE id_user = {$id} AND is_published = 1";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }
    
    public function user($user_id, $pagination = false){
        $sql = "SELECT text, date FROM comments c WHERE c.id_user ={$user_id} AND is_published = 1";
        if(!$pagination)
        {
            $sql.= " ORDER BY date DESC ";
            $sql.= " LIMIT 5 ";
        } else {
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        }
        $result = $this->db->query($sql);
        return $result;
    }

    public function publish($id){
        $sql = "UPDATE comments SET is_published = 1 WHERE id = {$id}";;
        $result = $this->db->query($sql);
        return $result;        
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "DELETE FROM comments WHERE id = {$id} OR parent_id = {$id}";
        return $this->db->query($sql);
    }
}