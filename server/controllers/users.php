<?php

class users extends controller {
    //возвращаем список юзеров уже с пагинацией согласно данных gjkextyys[ lfyys[]]
    public function GET ($data){
        //если $data не целое положительное число, приравниваем к 1
        //чтоб избежать sql инъекции
        if (!is_int($data) && $data <= 0){
            $data = 1;
        };
        $offset = ($data - 1)*5;
        $arr = mysqli_fetch_all(mysqli_query($this->db, 'select name from students limit 5 offset ' . $offset));
        $cnt = mysqli_fetch_assoc(mysqli_query($this->db,'select count(*) as cnt from students'));
        echo json_encode([$arr,$cnt]);
    }
}