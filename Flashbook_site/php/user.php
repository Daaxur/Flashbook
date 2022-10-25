<?php
session_start();
require_once("../utils/db_connect.php");

if (!$_SESSION['connected']) {
    echo json_encode(["success" => false, "error" => "Vous n'êtes pas connecté"]);
    die;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') $method = $_POST;
else $method = $_GET;

switch ($method['choice']) {
    case "delete":
        $req = $db->prepare("DELETE FROM messages WHERE user_id = :id");
        $req->execute([$_SESSION['user_id']]);
        $req = $db->prepare("DELETE FROM isingroups WHERE user_id = :id");
        $req->execute([$_SESSION['user_id']]);
        $req = $db->prepare("DELETE FROM users WHERE user_id = :id");
        $req->execute([$_SESSION['user_id']]);

        echo json_encode(["success" => true]);
        break;


    case 'select':
        $req = $db->query("SELECT user_id, user_pseudo FROM users");

        while ($user = $req->fetch(PDO::FETCH_ASSOC)) $users[] = $user;

        echo json_encode(["success" => true, "users" => $users]);
        break;

    case 'select_isnotingroup':
        if (isset($method['group_id']) && !empty(trim($method['group_id']))) {
            $req = $db->prepare("SELECT DISTINCT u.user_id, user_pseudo FROM users u LEFT JOIN isingroups ug ON ug.user_id = u.user_id WHERE ug.user_id NOT IN (SELECT user_id FROM isingroups ug2 WHERE ug2.group_id = ?) OR ug.user_id IS NULL");
            $req->execute([$method['group_id']]);

            while ($user = $req->fetch(PDO::FETCH_ASSOC)) $users[] = $user;

            echo json_encode(["success" => true, "users" => $users]);
        } else echo json_encode(["success" => false, "error" => "Identifiant du groupe non renseigné"]);
        break;

    case "select_id":
        $req = $db->prepare("SELECT user_id, user_pseudo, user_picture FROM users WHERE user_id = ?");
        $req->execute([$_SESSION['user_id']]);

        $user = $req->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "user" => $user]);
        break;

    case 'update':
        if (
            isset($method['pseudo'], $method['picture']) &&
            !empty(trim($method['pseudo'])) &&
            !empty(trim($method['picture']))
        ) {
            $sql = "UPDATE users SET user_pseudo = :pseudo, user_picture = :picture WHERE user_id = :id ";
            $req = $db->prepare($sql);
            $req->bindValue(':pseudo', $method['pseudo']);
            $req->bindValue(':picture', $method['picture']);
            $req->bindValue(':id', $_SESSION['user_id']);
            $req->execute();

            echo json_encode(["success" => true]);
        } else echo json_encode(["success" => false, "error" => "Les données ne sont pas correctement renseignée"]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix n'existe pas"]);
        break;
}
