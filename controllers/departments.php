<?php
    namespace App\controllers;
    use App\models\departments as m_departments;
    class departments{
        protected $view;
        protected $departmens;
        public function __construct(){
            $this->view = new \View();
            include 'models/departments.php';
            $this->departmens = new m_departments();
        }
        
        public function getDepartmentById($dat){
            if($dat['dep_id']){
                $dat = $dat['dep_id'];
            }
            $res = $this->departmens->getDepartmentById($dat);
            //\Helper::prin(json_decode($res));
            return $res;
        }
        
    }
?>