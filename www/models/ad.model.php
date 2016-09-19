<?php

class AdModel extends Model
{
    public function getList(){
        $sql = "SELECT * FROM ad";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id=(int)$id;
        $sql = "select * from ad where id = '{$id}'" ;
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function imageProcessing($image, $company)
    {
        $full_path_for_image = '';
        
            switch($image['type'])
            {
                case 'image/jpeg': $ext = 'jpg'; break;
                case 'image/jpg': $ext = 'jpg'; break;
                case 'image/gif':  $ext = 'gif'; break;
                case 'image/png':  $ext = 'png'; break;
                case 'image/tiff': $ext = 'tif'; break;
                default:           $ext = '';    break;
            }
        
            if ($ext)
            {
                $path = "./img/ad/{$company}";
                if(!file_exists($path)){
                    mkdir($path);
                }
                //$path = substr($path, 1);
                $full_path_for_image = $path."/image".".".$ext;
                move_uploaded_file($image['tmp_name'], $full_path_for_image);
//                var_dump($full_path_for_image);
//                var_dump($image['tmp_name']);
//                exit;
            }
        
        return (substr($full_path_for_image, 1));
    }
    
    public function save($data, $image, $id = null){

        $id = (int)$id;
        $name = $this->db->escape(Validate::fixString($data['name']));
        $price = $this->db->escape(Validate::fixString($data['price']));
        $href = $this->db->escape(Validate::fixString($data['href']));
        $company = $this->db->escape(Validate::fixString($data['company']));
        $telephone = $this->db->escape(Validate::fixString($data['telephone']));
        $counter = isset($data['counter']) ? $data['counter'] : 0;
        $is_published = isset($data['is_published']) ? 1 : 0;

        var_dump($image);
        if($image['image']['size'] > 0 )
        {
            $image_path = $this->imageProcessing($image['image'], $company);
            $image_sql = " , image = '{$image_path}'";
        }

        if(!$id){ // Add new record
            $sql = "
            INSERT INTO ad
                SET name = '{$name}',
                    price = '{$price}',
                    href = '{$href}',
                    company = '{$company}',
                    telephone = '{$telephone}',
                    is_published = '{$is_published}'
                    {$image_sql}
            ";
        } else {// Update existing record
            $sql = "
            UPDATE ad
                SET name = '{$name}',
                    price = '{$price}',
                    href = '{$href}',
                    company = '{$company}',
                    counter = '{$counter}',
                    telephone = '{$telephone}',
                    is_published = '{$is_published}'
                    {$image_sql}
                WHERE id = {$id}
            ";
        }

        return $this->db->query($sql);
    }

    public function counter($id){
        $id = $this->db->escape(Validate::fixString($id));
        $sql = "SELECT counter FROM ad WHERE id = '{$id}'";
        $result = $this->db->query($sql);
        $counter = $result[0]['counter'] + 1;
        $sql = "UPDATE ad SET counter = '{$counter}' WHERE id = {$id}";
        return $this->db->query($sql);
    }
    

    public function delete($id){
        $id = $this->db->escape(Validate::fixString($id));
        $sql = "delete from ad where id = {$id}";
        return $this->db->query($sql);
    }
}