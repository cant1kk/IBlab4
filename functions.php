<?php
include "connect.php";

if (isset($_POST['reg'])) {
    $login = $_POST["login"];
    $password = $_POST["password"];
    preg_match_all("/\d+/", $password, $numberPassword);
    $bigInt = 1;
    $newPassword = $password;
    foreach($numberPassword[0] as $number) {
        $bigInt = $bigInt * $number;
    }
    foreach($numberPassword[0] as $index => $number) {
        $newPassword = str_replace($number, $bigInt, $password);
    }
    if($password != $newPassword) {
        $user = R::dispense('users');
        $user->login = $login;
        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user = R::store($user);
        $_SESSION["user"] = $user;
        die("Вы зарегистрировались");
    }
}
if(isset($_POST["auth"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];
    $user = R::findOne("users", "login = ?", array($login));
    if($user) {
        if(password_verify($password,$user['password'])) {
            $_SESSION["user"] = [
                'id' => $user["id"],
                "login" => $user["login"],
            ];
            die("вы авторизовались");
        }
    }else{
        die("Не верный логин и пароль");
    }
}
