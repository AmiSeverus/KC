<?php

class auth extends controller {
    public function POST ($data){
        //если в POST пишел ключ - token - проверяем авторизован ли юзер по нему,
        //если нет - по username и password
        if (!$data['token']) {
            //используем md5 для:
            //-уникальности
            //-отсутствия открытых данных в базе данных
            //предосвращения sql-инъекции
            $id = mysqli_fetch_assoc(mysqli_query($this->db, "select id from api_users where username = '" . md5($data['login']) . "' and password = '" .  md5($data['password']) ."'"))['id'];
            if ($id){
                //используем md5 от спец.функции для криптоскойких (согласно документации) значений
                $token = md5(random_int(PHP_INT_MIN, PHP_INT_MAX));
                mysqli_query($this->db, "update api_users set token = '" . $token . "' where id = " . $id);
                echo json_encode(['status'=>'succes', 'token'=>$token]);
            } else {
                echo json_encode(['status'=>'error']);
            }
        } else {
            $token = $data['token'];
            $user = mysqli_fetch_assoc(mysqli_query($this->db, "select * from api_users where token = '" . mysqli_real_escape_string($this->db, $token) . "'"));
            if ($user){
                echo json_encode(['status'=>'succes']);
            } else {
                echo json_encode(['status'=>'error']);
            }
        }
    }
    //обнуляем токен в базе данных
    public function DELETE ($token){
        mysqli_query($this->db, "update api_users set token = NULL where token = '" . mysqli_real_escape_string($this->db, $token) . "'");
    }
}