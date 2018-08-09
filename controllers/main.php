<?php
    namespace App\controllers;
    use App\controllers\tasks as c_tasks;
    class main{
        protected $task;
        public function __construct(){
            $this->task =  new c_tasks();
        }
        public function index(){
                require_once "views/main/header.php";
                require_once 'views/main/topbar.php';
                $this->task->getSubtask();
                $this->task->index();
                require_once "views/main/foother.php";
        }
    }
?>