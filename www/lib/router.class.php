<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.08.2016
 * Time: 10:13
 */
class Router{
    protected $uri;
    protected $controller;
    protected $action; // метод
    protected $params;
    protected $route;
    protected $method_prefix;
    protected $language;

    public function __construct($uri)
    {
        // urldecode — Декодирование URL-кодированной строки
        $this->uri = urldecode(trim($uri, '/'));

        $routes = Config::get('routes');
        $this->route = Config::get('default_route'); // 'default_route' = 'default'
        $this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : ''; // если не админ, то = ''
        $this->language = Config::get('default_language'); // en
        $this->controller = Config::get('default_controller'); // pages
        $this->action = Config::get('default_action'); // index

        $uri_parts = explode('?', $this->uri);

        $path = $uri_parts[0]; // мы не работает вообще с GET параметров

        $path_parts = explode('/', $path);

        // prefix/lng/controller/action/param1/param2/param3 ...
        if( count($path_parts)){
            // Является ли текущий current элемент массива $path_parts ключем массива $routes
            if( in_array(strtolower(current($path_parts)), array_keys($routes))){
                $this->route = strtolower(current($path_parts));
                $this->method_prefix = isset($routes[$this->route])? $routes[$this->route] : '';
                array_shift($path_parts);
            // Является ли текущий элемент значением масиива languages
            } elseif (in_array(strtolower(current($path_parts)), Config::get('languages'))){
                $this->language = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            if(current($path_parts)){
                // Класс
                $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            if(current($path_parts)){
                // Метод
                $this->action = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            $this->params = $path_parts;
        }
    }

    public static function redirect($location){
        header("Location: $location");
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getMethodPrefix()
    {
        return $this->method_prefix;
    }

   public function getLanguage()
    {
        return $this->language;
    }
}