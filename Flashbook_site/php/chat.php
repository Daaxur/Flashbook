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
    case "select_chats":
        if (isset($method['group_id']) && !empty(trim($method['group_id']))) {
            $req = $db->prepare("SELECT m.*, u.user_pseudo, u.user_picture FROM messages m INNER JOIN users u ON u.user_id = m.user_id WHERE group_id = ?");
            $req->execute([$method['group_id']]);

            while ($message = $req->fetch(PDO::FETCH_ASSOC)) $messages[] = $message;

            echo json_encode(["success" => true, "messages" => $messages]);
        } else echo json_encode(["success" => false, "error" => "erreur"]);
        break;

    case "insert_chat":
        if (isset($method['content'], $method['group_id']) && !empty(trim($method['content'])) && !empty(trim($method['group_id']))) {
            $req = $db->prepare("INSERT INTO messages (message_content, user_id, group_id) VALUES (?, ?, ?)");
            $req->execute([$method['content'], $_SESSION['user_id'], $method['group_id']]);
            $group_id = $db->lastInsertId();

            echo json_encode(["success" => true, 'message_id' => $db->lastInsertId()]);
        } else echo json_encode(["success" => false, "error" => "Les données ne sont pas correctement renseignée"]);
        break;

    //case "delete_chat":
         //if (isset($method['id']) && !empty(trim($method['id']))) {
            // $req = $db->prepare("DELETE FROM messages WHERE message_id = ?");
             //$req->execute([$method['id']]);

           //echo json_encode(["success" => true]);
         //} else echo json_encode(["success" => false, "error" => "Suppression impossible"]);
        //break;

    //default:
        //echo json_encode(["success" => false, "error" => "Ce choix n'existe pas"]);
        //break;
}
