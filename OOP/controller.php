<?php
require_once ("C:\OS\OSPanel\domains\localhost\OOP\model.php");
//Класс для парсинга данных
class Parse extends Curl {
    public $a = array();
    public function Parser() {
        $html = Curl::Curl_get('https://www.habr.com/all/'); //Распарсиваем страницу cURL'ом
        $saw = new nokogiri($html);
        $comm = $saw->get('.post__title_link')->toArray();
        foreach ($comm as $key => $value) {
            $hrefArticle = $value['href'];
            $href = Curl::Curl_get($hrefArticle);
            $res = new nokogiri($href);
            $date = $res->get('.post__time')->toArray();
            $nameArticle = $res->get('.post__title-text')->toArray();
            $count = $res->get('#comments_count')->toArray();
            $summ = array_merge($date ,$nameArticle ,$count); //склеиваем все три массива спарсенных данных
            foreach ($summ as $key => $value) {
                $a[] = $value['#text']['0'];
            }
        }
        return($a);
    }
}

$classRead = new Read;
$Read = $classRead -> Read();

class Curl{
    public $ch;
    public static function Curl_get($url){ //Притворяемся пользователем дабы избежать бан по IP
        $referer = 'https://www.google.com';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

require_once ('C:\OS\OSPanel\domains\localhost\OOP\index.php');
?>