<?php

session_start();
require_once("../utils/db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(["success" => false, "error" => "Problème de méthode"]);
    die;
}

if (isset($_POST['pseudo'], $_POST['pwd']) && !empty(trim($_POST['pseudo'])) && !empty(trim($_POST['pwd']))) {
    $req = $db->prepare("SELECT user_id, user_pwd, user_pseudo, user_picture, user_isadmin FROM users WHERE user_pseudo = ?");
    $req->execute([$_POST['pseudo']]);

    $user = $req->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['pwd'], $user['user_pwd'])) {
        $_SESSION['connected'] = true;
        $_SESSION['user_id'] = $user['user_id'];

        unset($user['user_pwd']);

        echo json_encode(["success" => true, "user" => $user]);
    } else echo json_encode(["success" => false, "error" => "Le login n'a pas pu aboutir"]);
} else echo json_encode(["success" => false, "error" => "Remplissez les champs"]);

