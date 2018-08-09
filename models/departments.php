<?php
    namespace App\models;
    use Illuminate\Database\Eloquent\Model;
    class departments extends Model{
        public $timestamps = false;
        public $table = 'department';
        
        public function getDepartmentById($id){
            $results =  departments::leftJoin('Kompany', function($join) {$join->on('Kompany.id', '=', 'department.id_kompany');})->where('department.id','=',$id)->get();
            return $results;
        }
    }
?>