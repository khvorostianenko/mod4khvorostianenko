<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 03.08.2016
 * Time: 15:33
 */
class PagesModel extends Model{
    
    public function getList(){
        $sql = "SELECT c.*, n.id as id_news, n.title, n.date, n.id_categories, n.analitycs FROM categories c 
                    LEFT JOIN news n ON c.id = n.id_categories
                    WHERE c.is_published='1' AND n.is_published='1'
                    ORDER BY c.show_method, n.date DESC";
        return $this->db->query($sql);
    }

    public function getListForSlider(){
        $sql = "SELECT id, title, content FROM news WHERE is_published='1' AND content LIKE '%<img%' ORDER BY date DESC LIMIT 3";
        $result = $this->db->query($sql);
        echo '<meta charset="utf-8">';

        for($i = 0; $i<count($result); $i++){
            preg_match('/src=".+?"/s', $result[$i]['content'], $matches);

            $result[$i]['image'] = $matches[0];

        }
        return $result;
    }
}