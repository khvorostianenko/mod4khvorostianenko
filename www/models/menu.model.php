<?php

class MenuModel extends Model{
    
    public function getList($only_published = false){
        $sql = "select * from menu";
        if($only_published){
            $sql.= " and is_published = 1";
        }
        return $this->db->query($sql);
    }

    public function save($data, $id=null){

        $id = (int)$id;
        $name = $this->db->escape(Validate::fixString($data['name']));
        $href = $this->db->escape($data['href']);
        $show_method = $this->db->escape(Validate::fixString($data['show_method']));
        $parent_id = $this->db->escape(Validate::fixString($data['parent_id']));
        $table = $this->db->escape(Validate::fixString($data['table']));


        if(!$id){ // Add new record
            $sql = "
            INSERT INTO menu
                SET name = '{$name}',
                    href = '{$href}',
                    show_method = '{$show_method}',
                    parent_id = '{$parent_id}'
            ";
        } else {// Update existing record
            $sql = "
            UPDATE menu
                SET name = '{$name}',
                    href = '{$href}',
                    show_method = '{$show_method}',
                    parent_id = '{$parent_id}'
                WHERE id = {$id}
            ";
        }

        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "delete from menu where id = {$id}";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id=(int)$id;
        $sql = "select * from menu where id = '{$id}' limit 1" ;
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    
}