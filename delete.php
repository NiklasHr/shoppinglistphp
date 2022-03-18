<?php 
require_once './inc/headers.php';
require_once './inc/functions.php';

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Accept, Content-Type, Access-Control-Allow-Header');

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_SPECIAL_CHARS);

try {
    $db = openDb();
    $query = $db -> prepare('delete from item where id=(:id)');
    $query -> bindValue(':id',$id,PDO::PARAM_INT);
    $query -> execute();
    header('HTTP/1.1 200 OK');
    $data = array('id' => $id);
    print json_encode($data);
} catch (PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex -> getMessage());
    print json_encode($error);
}