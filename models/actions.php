<?php
    namespace App\models;
    use Illuminate\Database\Eloquent\Model;
    class actions extends Model{
        public $timestamps = false;
        public $table = 'actions';
        
        public function addAction($data){
           actions::insertGetId(['action'=>$data['action'],'id_user'=>USERID,'id_department'=>DEPARTMENT,'date_action'=>\Carbon\Carbon::now()]);
        }
        
        public function getActions(){
            $date = date('Y-m-d 0:0:0');
            $results =  actions::leftJoin('department', function($join) {$join->on('department.id', '=', 'actions.id_department');})->leftJoin('users', function($join) {$join->on('users.id', '=', 'actions.id_user');})->leftJoin('Kompany',function($join) {$join->on('Kompany.id', '=', 'department.id_kompany');})->where('actions.id_department',DEPARTMENT)->where('actions.date_action','>',$date)->where('actions.id_user','!=',USERID)->get();
            return $results;
        }
        
    }
?>