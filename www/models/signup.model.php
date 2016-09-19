<?php
class SignupModel extends Model{
    
    public function emailCheck($email){
        $email = $this->db->escape($email);
        $sql = "SELECT * FROM users WHERE email='{$email}'";
        $result = $this->db->query($sql);
        if( isset($result[0])){
            return false;
        }
        return true;
    }
    
    
    public function userCreate($email, $password) {
        $password = $this->db->escape($password);
        $password_hash = Password::encryptPass($password);
        $sql = "INSERT INTO users VALUES( NULL, '{$email}', '{$password_hash}', 'user')";
        $result = $this->db->query($sql);
        if( isset($result)){
            return true;
        }
        return false;
    }
}