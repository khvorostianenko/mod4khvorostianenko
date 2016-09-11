<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 02.08.2016
 * Time: 11:45
 */
class App{
    protected static $router;

    public static $db;

    public static function getRouter()
    {
        return self::$router;
    }

    public static function run($uri){
        // Создаем класс Роутер, передаем uri, разбиваем ури на части:
        // Пример: http://www.mvc.com/contacts/
        self::$router = new Router($uri);
        
        // Создаем подключение к базе данных
        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        // Загружаем параметры языка, значение по умолчанию 'en'
        Lang::load(self::$router->getLanguage());

        // Класс контроллера $controller_class = ContactsController
        // ucfirst - upper case first
        $controller_class = ucfirst(self::$router->getController()).'Controller';

        // Метод контроллера  = префикс + метод. У нас  "" + index = index
        $controller_method = strtolower(self::$router->getMethodPrefix()).self::$router->getAction();

        $layout = self::$router->getRoute();
        if( $layout == 'admin' && Session::get('role') != 'admin'){
            if ($controller_method != 'admin_login'){
                Router::redirect('/admin/users/login');
            }
        }

        // Создаем новый экземепляр класс ContactsController
        $controller_object = new $controller_class();

        // Если есть у объекта ContactsController метод index  то
        if(method_exists($controller_object, $controller_method)){
            
            // Путь к шаблону = ContactsController->index() в нашем случае Null
            $view_path = $controller_object->$controller_method();
            
            // Новый Объект "представления"     ContactsController->getData(), путь определится в контрукторе (если не передать)
            // $controller_object->getData() - array(), $view_path = Null
            
            $view_object = new View($controller_object->getData(), $view_path);
            
            // Объект "представления" -> render()
            $content = $view_object->render();
        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.' does not exist.');
        }

        // Путь к главному шаблону  = ROOT.DS.'views'  /   default.html =  views / default.html
        $layout_path = VIEWS_PATH.DS.$layout.'.html';

        // Новый Объект "представления" =  views / default.html
        // compact - создает массив, содержащий названия переменных и их значения, то есть $content, которая определена в ифе выше
        $layout_view_object = new View(compact('content'), $layout_path);
        
        // Вывод на экран
        echo $layout_view_object->render();
    }
}