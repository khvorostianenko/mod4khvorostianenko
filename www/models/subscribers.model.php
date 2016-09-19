<?php

class SubscribersModel extends Model{

    public function getList(){
        $sql = "SELECT * FROM subscribers ORDER BY id DESC";
        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "delete from subscribers where id = {$id}";
        return $this->db->query($sql);
    }

    public function save($data){

        $name = Validate::fixString($data['name']);
        $name = $this->db->escape($name);
        $email = Validate::fixString($data['email']);
        $email = $this->db->escape($email);

        $sql = "INSERT INTO subscribers SET `name` = '{$name}',`email` = '{$email}'";
        return $this->db->query($sql);
    }

}