<?php
    namespace App\models;
    use Illuminate\Database\Eloquent\Model;
    class users extends Model{
        public $timestamps = false;
        public $table = 'users';
        public function getUsers(){ 
            return users::all();
        }  
        public function findUser($username){
            //$id_depatrtment = json_decode($this->getCurrentUser())[0]->id_department;
            $results =  users::leftJoin('department', function($join) {$join->on('department.id', '=', 'users.id_department');})->leftJoin('Kompany',function($join) {$join->on('Kompany.id', '=', 'department.id_kompany');})->where('users.id_department',DEPARTMENT)->where('users.id','!=',USERID)->where('name','like','%'.$username.'%')->select('users.*','users.id as user_id')->get();
            return $results;
        }  
        public function getCurrentUser($id=''){
            $id = (!empty($id)? $id : USERID);
            $results = users::where('id',$id)->get();
            return $results;
        }
        public function addUser($data){
            $data2 = \Helper::generatePassWidthHash($data['password']);
            $pas = $data2['password'];
            $hash = $data2['hash'];
            $id = users::insertGetId(['id_department'=>DEPARTMENT,'name'=>$data['name'],'f_letter'=>\Helper::getFirstLetter($data['name']),'password_md5'=>$pas,'hash'=>$hash,'first_name_user'=>$data['first_name_user'],'last_name_user'=>$data['last_name_user'],'email'=>$data['email'],'date_register'=>\Carbon\Carbon::now()]); 
            if($id){
                return true;
            }else{
                return false;   
            }            
        }
        public function checkUserEmalAndLogin($data){
            $res = users::where('name','=',$data['name'])->orWhere('email','=',$data['email'])->get();
            if($res){
                return $res;
            }else{
                return false;
            }
        }
        
    }
?>