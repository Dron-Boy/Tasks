<?php
    namespace App\controllers;
    use App\models\actions as m_actions;
    class actions{
        protected $view;
        protected $actions;
        public function __construct(){
            $this->view =  new \View();
            include 'models/actions.php';
            $this->actions =  new m_actions();
        }
        public function addAction($data){
            $this->actions->addAction($data);
        }
        
        public function getActions($data){
            $dat['actions'] = json_decode($this->actions->getActions($data));
            $dat['error']  = 'Недавних событий нет!';
            $this->returnData('notification/notification',$dat);
        }
        public function returnData($file,$data){
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                ob_start();
                $this->view->render($file,$data);
                $render = ob_get_clean();
                $data['content'] = $render;
                echo json_encode($data);
            }else{
                $this->view->render($file,$data);
            }
        }
    }
?>