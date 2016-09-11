<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 03.08.2016
 * Time: 15:32
 */
class Model{
    protected $db;

    public function __construct()
    {
        $this->db = App::$db;
    }
}