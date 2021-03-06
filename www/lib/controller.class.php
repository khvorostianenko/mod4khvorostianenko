<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.08.2016
 * Time: 11:55
 */
class Controller{
    protected  $data;

    protected $model;

    protected $params;

    public function __construct($data = array())
    {
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }


}