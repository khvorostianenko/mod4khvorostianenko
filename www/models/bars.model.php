<?php

class BarsModel extends Model{

    public function getAdList(){
        $sql_1 = "SELECT * FROM ad WHERE is_published = 1 ORDER BY counter DESC LIMIT 2";
        $result_sql_1 = $this->db->query($sql_1);

        $sql_2 = "SELECT * FROM ad 
                    WHERE is_published = 1
                    AND id != (SELECT id FROM ad ORDER BY counter DESC LIMIT 1)
	                AND id != (SELECT id FROM ad ORDER BY counter DESC LIMIT 1,1) 
	                ORDER BY RAND() LIMIT 6";
        $result_sql_2 = $this->db->query($sql_2);
        $count = count($result_sql_2);
        for ($i = 0; $i < $count; $i++){
            $result[$i + 2] = array_shift($result_sql_2);
        }
        $result = $result_sql_1 + $result;
        return $result;
    }
    
    public function get_5top_users(){
        $sql = "SELECT email, u.id, COUNT(*) as count_comments FROM users u 
                    JOIN comments c ON u.id = c.id_user 
                    GROUP BY c.id_user
                    ORDER BY count_comments DESC
                    LIMIT 5";
        return $this->db->query($sql);
    }

    public function get_3top_news(){
        $sql = "SELECT n.title, n.id, COUNT(*) as most_commented FROM news n 
                    JOIN comments c ON n.id = c.id_news
                    WHERE c.date > (NOW() - INTERVAL 60*24 MINUTE)
                    GROUP BY c.id_news 
                    ORDER BY most_commented DESC
                    LIMIT 3";
        return $this->db->query($sql);
    }

    public function get_tags(){
        $sql = "SELECT tags FROM news";
        $result = $this->db->query($sql);
        for($i = 0; $i < count($result); $i++){

                $tags = explode(',', $result[$i]['tags']);

                for($j = 0; $j < count($tags); $j++)
                {
                    $serach_tags[] =  trim($tags[$j]);

                }

        }
        $serach_tags_unique = array_unique($serach_tags);
        asort($serach_tags_unique);
        return $serach_tags_unique;
    }
    
    public function get_categories(){
        $sql = "SELECT * FROM categories WHERE is_published = 1 ORDER BY show_method";
        return $this->db->query($sql);
    }

    public function getStyle(){
        $sql = "SELECT * FROM style";
        return $this->db->query($sql);
    }
    
    public function getNavList(){
        $sql = "SELECT * FROM menu ORDER BY show_method";
        return $this->db->query($sql);
    }
}