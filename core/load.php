<?php
    namespace App\core;
    class load{
        public $controller_name;
        public $model_name;
        public $action_name;
        public function __construct(){
            $this->controller_name =  'main';
            $this->model_name = 'tasks';
            $this->action_name =  'index';
        }
        public function load(){
            $routes = explode('/',$_SERVER['REQUEST_URI']);
            if(!empty($routes[1])){
                $this->controller_name = $routes[1];
                $this->model_name = $routes[1];
            }
            if(!empty($routes[2])){
                $this->action_name = $routes[2];
            }
            $filename_controller = "controllers/".$this->controller_name.".php";
            $filename_model = "models/".strtolower($this->model_name).".php";
            try{
                //Подключение контролера
                if(file_exists($filename_controller)){
                    require_once $filename_controller;
                }else{
                    throw new Exception('File not found: '.$filename_controller);
                }
                $classname ="\App\controllers\\".$this->controller_name;
                if(class_exists($classname)){
                    $controller = new $classname();
                }else{
                    throw new Exception('Class not found: '.$classname);
                }
                $controller_class = $this->action_name;
                if(method_exists($controller,$controller_class)){
                    $controller->$controller_class($_REQUEST);
                }else{
                    throw new Exception('Method not found: '.$controller_class );
                }
                
            }catch(Exception $e){
                if(file_exists('debug.txt')){
                    echo $e->getMessage();
                }else{
                    require_once "errors/404.php";
                }
            }
        }
    }
?>