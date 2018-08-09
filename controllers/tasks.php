<?php
    namespace App\controllers;
    use App\models\tasks as m_tasks;
    use App\controllers\actions as c_actions;
    use App\controllers\users as с_users;
    class tasks{
        protected $view;
        protected $task;
        protected $user;
        protected $actions;
        public function __construct(){
            $this->view =  new \View();       
            $this->task =  new m_tasks();
            $this->user =  new с_users();
            $this->actions = new c_actions();
        }
        public function index($dat=''){
            $data['title'] = 'Все задачи';
            $data['error'] = 'Пока задач нет!';
            $tasks = $this->task->index(USERID);     
            $data['tasks'] = json_decode($tasks);
            //\Helper::prin($data);
            $this->returnData('tasks/task_list',$data);
        }
        public function getCompleteTask($dat){
            $data['title'] = 'Выполненые задачи';
            $data['error'] = 'Нет выполненых задач';
            $tasks = $this->task->getCompleteTask(USERID);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        public function findTask($dat){
            $data['title'] = 'Найденые задачи';
            $data['error'] = 'Не найдено задач по значению: '.$dat['search'];
            $tasks = $this->task->findTask($dat['search'],USERID);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        
        public function getById($dat){
            //$id = (!empty($dat['id_task']) ? $dat['id_task'] : $dat['task_id']);
            $id = $dat;
            $data['title'] = 'Детальная информация о задаче';
            $tasks = $this->task->getTasksById($id);            
            $data['tasks'] = json_decode($tasks);
            return $data;
            //$this->returnData('tasks/task_list',$data);
        }
        public function getConsolidateTask($dat){
            $data['title'] = 'Закрепленные задачи';
            $data['error'] = 'Закрепленных задач нет!';
            $tasks = $this->task->getConsolidateTask();     
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        public function getSetTask($dat){
            $data['title'] = 'Поставленные задачи';
            $data['error'] = 'Поставленных задач нет!';
            $id_user = (!empty($dat['id_user']) ? $dat['id_user'] : USERID);
            $tasks = $this->task->getSetTask($id_user);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        public function getAcceptTask($dat){
            $data['title'] = 'Принятые задачи';
            $data['error'] = 'Принятых задач нет!';
            $id_user = (!empty($dat['id_user']) ? $dat['id_user'] : USERID);
            $tasks = $this->task->getAcceptTask($id_user);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        public function getNoAcceptTask($dat){
            $data['title'] = 'Непринятые задачи';
            $data['error'] = 'Непринятых задач нет!';
            $id_user = (!empty($dat['id_user']) ? $dat['id_user'] : USERID);
            $tasks = $this->task->getNoAcceptTask($id_user);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        
        public function addComment($dat){
            $tasks = $this->task->addComment($dat); 
            if($tasks){
                $data['response']  = 'Комментарий добавлен!';
                $data['id_task'] = $dat['id_task'];
                $task = $this->getById($dat['id_task']);
                //\Helper::prin($task);
                $name = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name; 
                $this->add_action(['action'=>'Добавлен комментарий к задаче <b>'.$task['tasks'][0]->name_task.'</b> от пользователя <b>'.$name.'</b>']);
                echo json_encode($data);
                return true;
            }
            $data['error']  = 'Ошибка! Комментарий не добавлен';
            echo json_encode($data);
            return false;
        }
        
        public function recallTask($dat){
            $tasks = $this->task->recallTask($dat); 
            if($dat['datetime_recall']){
                $data['response'] = 'Напоминание установлено на: '.$dat['datetime_recall'];
            }else{
                $data['response'] = 'Напоминание удалено!';
            }
            echo json_encode($data);
        }
        
        public function add_action($data){
            //\Helper::prin($this);
            $this->actions->addAction($data);
        }
        
        public function addTask($dat){
            $tasks = $this->task->addTask($dat); 
            if($tasks){
                $data['response']  = 'Задача поставлена!';
                $name = $this->user->getCurrentUser(['id_user'=>$dat['id_user'],'view'=>1])['users'][0]->name;
                $name2 = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name;
                $this->add_action(['action'=>'Поставленна задача <b>'.$dat['name'].'</b>  пользователю <b>'.$name.'</b>  от пользователя <b>'.$name2.'</b> ']);
                echo json_encode($data);
                return true;
            }
            $data['error']  = 'Ошибка! Задача не поставлена!';
            echo json_encode($data);
            return false;
        }
        
        public function updateTask($dat){
            $tasks = $this->task->updateTask($dat);
            $task = $this->getById($dat['task_id']);
            $task = $task['tasks'];
            $name = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name; 
            $this->add_action(['action'=>'Пользователь '.$name.' отредактировал задачу <b>"'.$task[0]->name.'"</b>']);
            $this->index();
        }
        
        public function updateStatusTask($dat){
            $tasks = $this->task->updateStatusTask($dat);
            if($dat['status'] == 1){
                $task = $this->getById($dat['task_id']);
                $task = $task['tasks'];
                $name = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name;
                $this->add_action(['action'=>'Пользователь '.$name.' выполнил задачу <b>"'.$task[0]->name.'"</b>']);
            }
            $this->index();
        }
        
        public function deleteTask($dat){
            $task = $this->getById($dat['task_id']);
            $task = $task['tasks'];
            $name = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name;
            $this->add_action(['action'=>'Пользователь '.$name.' удалил задачу <b>"'.$task[0]->name.'"</b>']);
            $tasks = $this->task->deleteTask($dat);
            $this->index();
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
        
        public function getRecallTask($dat){
            $task = $this->task->getRecallTask();
            $data['response'] = '';
            if($task){
                $data['response'] = 'Напоминание о задаче: '.$task[0]->name_task;
            }
            echo json_encode($data);
        }
        
        public function getSubtask($dat=''){
            $id = (!empty($dat['id']) ? $dat['id'] : '');
            $tasks = $this->task->getSubtask($id);
            $data['user'] = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0];
            $data['user']->fletter =  $data['user']->f_letter;
            $data['tasks'] = json_decode($tasks);
            $data['title'] = 'Подзадачи';
            $data['error'] = 'Подзадач не найдено';            
            $this->returnData('tasks/sitebar',$data);
        }
        
        
        public function getNoCompleteTask($dat){
            $data['title'] = 'Не выполненые задачи!';
            $data['error'] = 'На сегодня нет не выполненых задач!';
            $id_user = (!empty($dat['id_user']) ? $dat['id_user'] : USERID);
            $tasks = $this->task->getNoCompleteTask($id_user);            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        
        public function getTodayTask($dat){
            $data['title'] = 'Задачи на сегодня!';
            $data['error'] = 'На сегодня задач нет!';
            $tasks = $this->task->getTodayTask();            
            $data['tasks'] = json_decode($tasks);
            $this->returnData('tasks/task_list',$data);
        }
        
        public function asseptTask($dat){
            $tasks = $this->task->asseptTask($dat);
            $task = $this->getById($dat['task_id']);
            $task = $task['tasks'];
            $name = $this->user->getCurrentUser(['id_user'=>USERID,'view'=>1])['users'][0]->name;
            $this->add_action(['action'=>'Пользователь <b>'.$name.'</b> принял задачу <b>"'.$task[0]->name.'"</b> на исполнение.']);
            unset($dat['id_user'],$dat['id_task']);
            $this->getAcceptTask($dat);
        }
        
    }
?>