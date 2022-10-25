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
    case 'select_groups':
        $req = $db->prepare("SELECT g.* FROM groups g INNER JOIN isingroups ug ON ug.group_id = g.group_id WHERE user_id = ?");
        $req->execute([$_SESSION['user_id']]);

        while ($group = $req->fetch(PDO::FETCH_ASSOC)) $groups[] = $group;

        echo json_encode(["success" => true, "groups" => $groups]);
        break;

    case 'select_id':
        if (isset($method['group_id']) && !empty(trim($method['group_id']))) {
            $req = $db->prepare("SELECT group_name FROM groups WHERE group_id = ?");
            $req->execute([$method['group_id']]);

            $group = $req->fetch(PDO::FETCH_ASSOC);

            echo json_encode(["success" => true, "group" => $group]);
        }
        echo json_encode(["success" => false, "error" => "L'identifiant du groupe n'a pas été renseigné"]);
        break;

    case "create_group":
        if (isset($method['name']) && !empty(trim($method['name']))) {
            $req = $db->prepare("INSERT INTO groups (group_name) VALUES (?)");
            $req->execute([$method['name']]);
            $group_id = $db->lastInsertId();

            $req = $db->prepare("INSERT INTO isingroups (user_id, group_id) VALUES (?, ?)");
            $req->execute([$_SESSION['user_id'], $group_id]);

            echo json_encode(["success" => true, 'group_id' => $group_id]);
        } else echo json_encode(["success" => false, "error" => "Les données ne sont pas correctement renseignée"]);
        break;

    case "add_user":
        if (isset($method['user_id'], $method['group_id']) && !empty(trim($method['user_id'])) && !empty(trim($method['group_id']))) {
            $req = $db->prepare("INSERT INTO isingroups (user_id, group_id) VALUES (?, ?)");
            $req->execute([$method['user_id'], $method['group_id']]);

            echo json_encode(["success" => true]);
        } else echo json_encode(["success" => false, "error" => "Les données ne sont pas correctement renseignée"]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix n'existe pas"]);
        break;
}
