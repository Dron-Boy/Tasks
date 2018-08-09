<?php
    namespace App\models;
    use Illuminate\Database\Eloquent\Model;
    class tasks extends Model{
       
        public $timestamps = false;
        public $table = 'tasks';
        public function index($data){
            $id_user = (!empty($data['id_user']) ? $data['id_user'] : USERID);
            $this->table = 'tasks';
            $results =  tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('tasks.parent_id',0)->groupBy('tasks.id')->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }  
        
        public function getConsolidateTask(){
            $results =  tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->where('user_to_task.id_user',USERID)->where('tasks.parent_id',0)->where('tasks.consolidate',1)->groupBy('tasks.id')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function getComentsToTask($id){
            $this->table = 'coments';
            return tasks::leftJoin('users', function($join) {$join->on('users.id', '=', 'coments.id_user');})->where('coments.id_task',$id)->get();    
        }
        
        public function getTasksById($id){
            $this->table = 'tasks';
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->where('tasks.id',$id)->groupBy('tasks.id')->select('tasks.*','tasks.name as name_task','tasks.id as id_task_t')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function getSubtask($id=''){
            $this->table = 'tasks';
            if(empty($id)){
                $res = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->where('user_to_task.id_user',USERID)->where('tasks.parent_id',0)->limit(1)->orderBy('date_add')->get();
                $res = json_decode($res);
                if(!$res){
                    return false;
                }
                $id = $res[0]->id;
            }
            return tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->where('tasks.parent_id',$id)->groupBy('tasks.id')->get();
        }
        
        public function findTask($name,$id_user=''){
            $this->table = 'tasks';
            $id_user = (!empty($id_user) ? $id_user : USERID);
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('tasks.name','like','%'.$name.'%')->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','users.*')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function addUsserForTask($results){
            $day = date('Y-m-d');
            foreach($results as $key=>$res){
                $this->table = 'users';
                if($res->set_the_task == 1){
                    $r = tasks::leftJoin('user_to_task', function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.set_the_task',0)->where('user_to_task.id_task',$res->id_task_t)->get();
                    $results[$key]->name_task = $res->name_task;
                    $results[$key]->name_user_set = $res->name;
                    $results[$key]->id_user_set = $res->user_id;
                    $results[$key]->clas = 'set_task';
                    $results[$key]->title = 'Задача поставлена полюзователю: '.$r[0]->name;
                    $results[$key]->fletter = $res->f_letter;
                    $results[$key]->accept = '<a class="link ico" url="tasks/deleteTask/?task_id='.$res->id_task_t.'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }else{
                    if($res->accept_the_task  == 0){
                        $results[$key]->clas = 'no_accept_task';
                        $results[$key]->title = 'Задача не принята на исполнение!';
                        $results[$key]->accept = '<a class="link ico" url="tasks/asseptTask/?task_id='.$res->id_task_t.'&id_user='.USERID.'&accept_the_task=1"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>';
                    }else{
                        $results[$key]->clas = 'accept_task';
                        $results[$key]->title = 'Задача принята для исполнения!';
                        if($res->complete == 0){
                            if($res->date_end < $day){
                                $results[$key]->clas = 'no_complate_task';
                                $results[$key]->title = 'Задача принята для исполнения, но не выполнена в срок!';
                            }
                            $results[$key]->accept = '<a class="link ico" url="tasks/updateStatusTask/?task_id='.$res->id_task_t.'&status=1"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';
                        }else{
                            $results[$key]->clas = 'complate_task';
                            $results[$key]->title = 'Задача выполнена';
                        }
                    }
                    $r = tasks::leftJoin('user_to_task', function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.set_the_task',1)->where('user_to_task.id_task',$res->id_task_t)->get();
                   // \Helper::prin(json_decode($r));
                    $results[$key]->name_user_set = $r[0]->name;
                    $results[$key]->id_user_set = $r[0]->id;
                    $results[$key]->fletter = $r[0]->f_letter;
                }
                $coments = $this->getComentsToTask($res->id_task_t);
                $results[$key]->coments = json_decode($coments);
            }
            $this->table = 'tasks';
            return $results;
        }
        
        public function getCompleteTask($id_user=''){
            $this->table = 'tasks';
            $id_user = (!empty($id_user) ? $id_user : USERID);
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('complate_task.id_user',$id_user)->where('complate_task.complete',1)->where('user_to_task.id_user',$id_user)->where('tasks.parent_id',0)->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->groupBy('tasks.id')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function getNoCompleteTask($id_user=''){
            $day = date('Y-m-d');
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('complate_task.complete',0)->where('tasks.parent_id',0)->where('tasks.date_end','<',$day)->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->groupBy('tasks.id')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function getSetTask($id_user){
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('tasks.parent_id',0)->where('user_to_task.set_the_task',1)->groupBy('tasks.id')->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->get();
            //dd($results);
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        public function getAcceptTask($id_user){
            $this->table = 'tasks';
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('user_to_task.accept_the_task',1)->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->groupBy('tasks.id')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function getNoAcceptTask($id_user){
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('user_to_task.accept_the_task',0)->where('user_to_task.set_the_task',0)->where('tasks.parent_id',0)->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->groupBy('tasks.id')->get();
            //dd($results);
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        public function getTodayTask(){
            $day = date('Y-m-d');
            $results = tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',USERID)->where('user_to_task.accept_the_task',1)->where('user_to_task.set_the_task',0)->where('tasks.date_end',$day)->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->groupBy('tasks.id')->get();
            if(!empty($results)){
                return $this->addUsserForTask($results);
            }else{
                return false;
            }
        }
        
        public function addTask($data){
           $id = tasks::insertGetId(['parent_id'=>$data['parent_id'],'name'=>$data['name'],'description'=>$data['description'],'date_add'=>\Carbon\Carbon::now(),'date_update'=>\Carbon\Carbon::now(),'date_end'=>$data['date_end']]); 
           $this->table = 'user_to_task';
           tasks::insertGetId(['id_user'=>USERID,'id_task'=>$id,'set_the_task'=>1,'accept_the_task'=>0]);
           tasks::insertGetId(['id_user'=>$data['id_user'],'id_task'=>$id,'set_the_task'=>0,'accept_the_task'=>0]);
           $this->table = 'complate_task';
           tasks::insertGetId(['id_task'=>$id,'id_user'=>$data['id_user'],'complete'=>0,'date_complete'=>'']);
           return true;
        }
        
        public function addComment($data){
             $this->table = "coments";
            
             tasks::insertGetId(['id_user'=>USERID,'id_task'=>$data['id_task'],'coment'=>$data['text'],'date'=>\Carbon\Carbon::now()]);
             return true;
        } 
        
        public function updateTask($data){
            $this->table = 'tasks';
            tasks::where('id',$data['task_id'])->update(['name'=>$data['name'],'description'=>$data['description'],'name'=>$data['name'],'date_update'=>\Carbon\Carbon::now(),'date_end'=>$data['date_end']]);
            $this->table = 'complate_task';
            tasks::where('id_task',$data['task_id'])->update(['complete'=>0,'date_complete'=>'']);            
        }
        
        public function updateStatusTask($data){
            $this->table = 'complate_task';
            if($data['status'] == 1){
                tasks::where('id_task',$data['task_id'])->update(['complete'=>$data['status'],'date_complete'=>\Carbon\Carbon::now()]);
                $mas = json_decode($this->getSubtask($data['task_id']));
                $this->table = 'complate_task';
                if($mas){
                    $str = [];
                    foreach($mas as $val){
                        $str[] = $val->id;
                    }
                    array_push($str,$data['task_id']);
                    tasks::whereIn('id_task',$str)->update(['complete'=>$data['status'],'date_complete'=>\Carbon\Carbon::now()]);
                }else{
                    tasks::where('id_task',$data['task_id'])->update(['complete'=>$data['status'],'date_complete'=>\Carbon\Carbon::now()]);
                }
            }else{
                tasks::where('id_task',$data['task_id'])->update(['complete'=>$data['status'],'date_complete'=>\Carbon\Carbon::now()]);
            }
            $this->table = 'tasks';
            return true;
        }
        
        public function getRecallTask(){
            $date = date('Y-m-d H:i:00');
            $id_user = (!empty($data['id_user']) ? $data['id_user'] : USERID);
            $this->table = 'tasks';
            $results =  tasks::leftJoin('user_to_task', function($join) {$join->on('tasks.id', '=', 'user_to_task.id_task');})->leftJoin('complate_task',function($join) {$join->on('tasks.id', '=', 'complate_task.id_task');})->leftJoin('users',function($join) {$join->on('users.id', '=', 'user_to_task.id_user');})->where('user_to_task.id_user',$id_user)->where('tasks.parent_id',0)->where('tasks.date_recall','=',$date)->groupBy('tasks.id')->select('user_to_task.*','user_to_task.id_user as user_id','tasks.*','tasks.name as name_task','tasks.id as id_task_t','complate_task.complete','complate_task.date_complete','users.*')->get();
            
            return json_decode($results);
        }
        public function asseptTask($data){
            $this->table = 'user_to_task';
            tasks::where('id_task',$data['task_id'])->where('id_user',$data['id_user'])->update(['accept_the_task'=>$data['accept_the_task']]);
            $this->table = 'tasks';
        }
        
        public function recallTask($data){
            $this->table = 'tasks';
            tasks::where('id',$data['task_id'])->update(['date_recall'=>$data['datetime_recall']]);
        }
        
        public function deleteTask($data){
            $this->table = 'tasks';
            tasks::where('id',$data['task_id'])->delete();
            $this->table = 'user_to_task';
            tasks::where('id_task',$data['task_id'])->delete();
            $this->table = 'complate_task';
            tasks::where('id_task',$data['task_id'])->delete();
            $this->table = 'tasks';
        }
    }
?>