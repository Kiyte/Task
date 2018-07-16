<?php
require_once __DIR__ . ("/vendor/autoload.php");

//Соединение с БД
class Connect {
    protected static function Link() {
        ORM::configure('mysql:host=localhost; dbname=Habr_inf');
        ORM::configure('username' , 'root');
        ORM::configure('password' , '10071994DA');
    }   
}

//Класс для парсинга данных
class Parse {
    private $res;
    private $href;
    private $href_article;
    private $date;
    private $comm;
    private $count;
    private $summ;
    public $a = array();
    public static function Parser() {
        $html = (file_get_contents('https://www.habr.com/all/'));
        $saw = new nokogiri($html);
        $comm = $saw->get('.post__title_link')->toArray();
            foreach ($comm as $key => $value) {
                $href_article = $value['href'];
                $href =(file_get_contents($href_article));       
                $res = new nokogiri($href); 
                $date = $res->get('.post__time')->toArray(); 
                $name_article = $res->get('.post__title-text')->toArray();
                $count = $res->get('#comments_count')->toArray();
                $summ = array_merge($date ,$name_article ,$count);
                $i = 0;
                foreach ($summ as $key => $value) {
                        $a[] = $value['#text']['0'];
            }
        }
        return($a); 
    }
}   

//Запись в БД (соединение с БД из class Connect($link_url) и массив спарсенных данных из class Parse ($massive) )
class Read extends Connect {
    public $read;
    public $massive;
    private $link_url;
    private $i;
    public function Read(){
        $link_url = Connect :: Link();
        $massive = Parse :: Parser();
        $i = 0;
        while ($i < 15) {
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
    private $connect;
    public $i;
    public $a = array();
    public function View($id)
    {
        $id_article = intval($id);
        $connect = Connect :: Link();
        $view = ORM::for_table ('Habr_inf') -> find_many();
        $i=0;
        foreach ($view as $key ) {
            if ($id_article == $i) {
                $a[] = $key['Name_s'] ; echo '&nbsp';
                $a[] = $key['Date_publ'] ; echo '&nbsp';
                $a[] = $key['Comment'] ; echo '&nbsp';
            }
            $i++;
        }
        //Здесь должен возвращаться массив $a
        return $a;
    }
}
?>