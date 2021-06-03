<?php

class users extends controller {
    //return a list of 5 users accorting data recieved from front
    //возвращаем список юзеров уже с пагинацией согласно данных front
    public function GET ($data, $token){
        $user = mysqli_fetch_assoc(mysqli_query($this->db, "select * from api_users where token = '" . mysqli_real_escape_string($this->db, $token) . "'"));
            if (!$user){
                echo json_encode(['status'=>'error']);
            } else {
            //if $data <=0 make it 1 to avoid possible sql injections
            //если $data не целое положительное число, приравниваем к 1
            //чтоб избежать sql инъекции 
            if (!is_int($data) && $data <= 0){
                $data = 1;
            };
            $offset = ($data - 1)*5;
            $arr = mysqli_fetch_all(mysqli_query($this->db, 'select name from students limit 5 offset ' . $offset));
            $cnt = mysqli_fetch_assoc(mysqli_query($this->db,'select count(*) as cnt from students'));
            echo json_encode([$arr,$cnt, ['status'=>'success']]);
        }
    }
}