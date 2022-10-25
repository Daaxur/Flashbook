<?php
session_start();
require_once("../utils/db_connect.php");
error_reporting(-1);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(['success' =>  false, "error" => "error"]);
    die;
}

if (
    isset($_POST['pseudo'], $_POST['pwd'], $_POST['email']) &&
    !empty(trim($_POST['pseudo'])) &&
    !empty(trim($_POST['pwd'])) &&
    !empty(trim($_POST['email']))
) {
    if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/", $_POST['pwd'])) {
        echo json_encode(['success' => false, "error" => "password error"]);
        die;
    }

    if (!preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,})$/i", $_POST['email'])) {
        echo json_encode(['success' => false, "error" => "email error"]);
        die;
    }

    $hash = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    $req = $db->prepare("INSERT INTO users(user_pseudo, user_pwd, user_email) VALUES (?, ?, ?)");
    $req->execute([$_POST['pseudo'], $hash, $_POST['email']]);

    echo json_encode(['success' => true]);
} else echo json_encode(['success' => false, "error" => "insert error"]);
