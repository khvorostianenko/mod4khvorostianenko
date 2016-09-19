<?php

class BackgroundModel extends Model{
    public function getList(){
        $sql = "select * from style";

        return $this->db->query($sql);
    }
    public function save($data){
        $id = Validate::fixString($data['id']);
        $id = $this->db->escape($id);
        $name = Validate::fixString($data['name']);
        $name = $this->db->escape($name);
        $color = Validate::fixString($data['color']);
        $color = $this->db->escape($color);

        // Update existing record

        $sql = "
            UPDATE style
                SET color = '{$color}'
                WHERE  id = {$id}
            ";
        return $this->db->query($sql);

    }

}