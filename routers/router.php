<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/11
 * Time: 15:02
 */
$db = new base($mysql_config);
$param = $_REQUEST;
$router = [
    '/' => function(){
        echo 'hi! I\'m Dawn';
    },
    '/insert' => function($db, $param) use ($db, $param){
        $res = $db->db('test')->insert([
            'name' => '123'
        ]);
        return $res;
    },
    '/delete' => function($db, $param) use ($db, $param){
        $res = $db->db('test')->where('id', '=', $param['id'])->delete();
        return $res;
    },
    '/update' => function($db, $param) use ($db, $param){
        $res = $db->db('test')->where('id', '=', $param['id'])->update($param);
        return $res;
    },
    '/query' => function($db, $param) use ($db, $param){
        $res = $db->db('test')->query();
        return $res;
    },
];
$ipos = stripos($_SERVER["REQUEST_URI"], '?');
$uri = $_SERVER["REQUEST_URI"];
if($ipos){
    $uri = substr($_SERVER["REQUEST_URI"],0,  $ipos);
}
echo json_encode($router[$uri]($db, $param));