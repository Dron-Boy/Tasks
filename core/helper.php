<?php
    class Helper{
        public static function prin($arr = []){
            echo'<pre>';
            print_r($arr);
            echo'</pre>';
        }
        public static function object_to_array($data){
            if (is_array($data) || is_object($data)){
                $result = array();
                foreach ($data as $key => $value){
                    $result[$key] = Helper::object_to_array($value);
                }
                return $result;
            }
            return $data;
        }
        public static function getFirstLetter($string){
            return mb_substr($string,0,1,"UTF-8");
        }
        
        public static function generatePassword($max = 10){
            $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; 
            $size = StrLen($chars)-1; 
            $password = ''; 
            while($max--){
                $password.=$chars[rand(0,$size)]; 
            }
            return $password;
        }
        
        public static function generatePassWidthHash($pass,$hash = ''){
            if(!$hash){
                $hash = Helper::generatePassword(30);
            }
            $pass = sha1(md5($pass.''.$hash));
            $data['password'] = $pass;
            $data['hash'] = $hash; 
            return $data;
        }
    }
?>