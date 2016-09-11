<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.08.2016
 * Time: 14:00
 */
class View{
    protected $data;

    protected $path;

    protected static function getDeafaultViewPath(){
        $router = App::getRouter();

        if(!$router){
            throw new Exception('Router not found');
        }
        // Контроллер Contacts
        $controller_dir = $router->getController();

        // Имя шаблона префикс " " + метод index + html = index.html
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';

        // путь: views/contacts/index.html
        return VIEWS_PATH.DS.$controller_dir.DS.$template_name;

    }

    public function __construct($data = array(), $path = null){

        if(!$path){
            // для Contacts $path = views/contacts/index.html
            $path = self::getDeafaultViewPath();
        }
        if(!file_exists($path)){
            throw new Exception('Template file is not found un path: '.$path);
        }

        // views/contacts/index.html
        $this->path = $path;
        // $data = array()

        $this->data = $data;
    }

    public function render(){
        $data = $this->data;

        ob_start();
        include($this->path);
        $content = ob_get_clean();
        return $content;
    }
}