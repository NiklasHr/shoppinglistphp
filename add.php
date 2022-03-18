<?php 
require_once './inc/headers.php';
require_once './inc/functions.php';

//Commented back end code seems to work in Postman, but I couldn't figure out how to get it to work in front end.

$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input -> description, FILTER_SANITIZE_SPECIAL_CHARS);
/* $amount = filter_var($input -> amount, FILTER_SANITIZE_SPECIAL_CHARS); */

try {
    $db = openDb();
    $query = $db -> prepare('insert into item(description) values (:description)');
    $query -> bindValue('description',$description,PDO::PARAM_STR);
    /* $query = $db -> prepare('insert into item(amount) values (:amount)');
    $query -> bindValue('amount',$description,PDO::PARAM_INT); */
    $query -> execute();
    header('HTTP/1.1 200 OK');
    $data = array('id' => $db -> lastInsertId(),'description' => $description/*, 'amount' => $amount*/);
    print json_encode($data);
} catch (PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex -> getMessage());
    print json_encode($error);
}