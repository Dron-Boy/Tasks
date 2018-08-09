<?php
    namespace App\controllers;
    use App\models\departments as m_departments;
    use App\models\users as m_users;
    class users{
        protected $view;
        protected $user;
        protected $dep;
        public function __construct(){
            $this->dep =  new m_departments();
            $this->view = new \View();
            $this->user = new m_users();
        }
        public function index(){
            $data['users'] = $this->user->getUsers();
            $data['title'] = 'Hello';
            $this->view->render('users/index',$data);
        }
        
        public function getCurrentUser($dat =[]){
            $view = (!empty($dat['view']) ? true : false);
            $id = (!empty($dat['id_user']) ?$dat['id_user'] : $dat);
            $data['users'] = json_decode($this->user->getCurrentUser($id));
            $data['title'] = 'Hello';
            $data['users'][0]->cur_user = USERID;
            if(!$view){
                $data['users'] = $data['users'][0];
                $data['departments'] = json_decode($this->dep->getDepartmentById($data['users']->id_department))[0];
                $this->returnData('users/user_info',$data);
                //\Helper::prin($data);
                return true;
            }
            $data['departments'] = json_decode($this->dep->getDepartmentById($data['users'][0]->id_department));
            return $data;
            //\Helper::prin($data);
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
        public function finduser(){
            $name = $_REQUEST['name'];
            $data['users'] = json_decode($this->user->findUser($name));
            $data['error'] = 'Пользователя по значению: '.$name.' не найдено!';
            $this->returnData('users/find',$data);
            //\Helper::prin(json_decode($data['users'])); 
        }
        
        public function generatePass($dat){
            $pass =  \Helper::generatePassword();
            $data['password'] = $pass;
            echo json_encode($data);
        }
        public function addUser($dat){
            if(!filter_var($dat['email'], FILTER_VALIDATE_EMAIL)){
                $data['error'] = 'E-mail не коректен!';
                echo json_encode($data);
                return false;
            }
            $res = json_decode($this->user->checkUserEmalAndLogin($dat));
            if($res != false){
                $res = $res[0];
                if($res->email){
                    $data['error'] = 'Аккаунт с таким E-mail уже существует';
                    echo json_encode($data);
                    return false;
                }
                if($res->name){
                    $data['error'] = 'Аккаунт с логином '.$dat['name'].' уже существует';
                    echo json_encode($data);
                    return false;
                }
            }
            if($this->user->addUser($dat)){
                $data['success'] = 'Пользователь добавлен!';
            }else{
                $data['error'] = 'Ошибка добавления пользователя!';
            }
            echo json_encode($data);
        }
    }
?>