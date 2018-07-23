<?php
require_once ('C:\OS\OSPanel\domains\localhost\OOP\controller.php');

//Соединение с БД
class Connect {
    protected static function Link() {
        ORM::configure('mysql:host=127.0.0.1:3306; dbname=Habr_inf');
        ORM::configure('username' , 'root');
        ORM::configure('password' , '');
    }
}

//Запись в БД (соединение с БД из class Connect($link_url) и массив спарсенных данных из class Parse ($massive) )
class Read extends Connect{
    public $read;
    public $massive;
    public function Read(){
        $ClassParse = new Parse;
        $massive = $ClassParse -> Parser();
        $linkUrl = Connect :: Link();
        $i = 0;
        $delete = ORM::for_table('Habr_inf') -> delete_many();//Затираем все данные в базе
       // $autoInc = ORM::for_table('Habr_inf') -> raw_query(' ALTER TABLE Habr_inf ALTER ID_inf SET DEFAULT 0 '); //Пытался сделать сброс автоинкрементного поля, но не получилось.
        while ($i < 15) {//Записываем данные о 5-ти статьях в базу
            $read = ORM::for_table ('Habr_inf') -> create();
            $read ->Date_publ = $massive[$i] ;
            $i++;
            $read ->Name_s = $massive[$i] ;
            $i++;
            $read ->Comment = $massive[$i] ;
            $i++;
            $read -> save();
        }
    }
}

//Вывод данных из БД
class View extends Connect{
    public $view;
    public $id;
    public $i;
    public $getId;
    public $a = array();
    public function View($getId){
        $IdGet = intval($getId);//Переводим пришедший ID из строки в целое число
        $connect = Connect :: Link();
        $view = ORM::for_table ('Habr_inf') -> find_many();
        $i=0;
        foreach ($view as $key ) { // Записываем нужные данные в массив $а
            If ( $i == $IdGet){
                $a[] = $key -> Name_s;
                $a[] = $key -> Date_publ;
                $a[] = $key -> Comment ;
                break;
            }else{$i++;
            }
        }
        return $a;
    }
}

require_once ('C:\OS\OSPanel\domains\localhost\OOP\index.php');
?>