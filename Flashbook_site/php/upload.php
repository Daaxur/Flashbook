<?php
session_start();

if (!$_SESSION['connected']) {
    echo json_encode(["success" => false, "error" => "Vous n'êtes pas connecté"]);
    die;
}

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];

    $location = "../images/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);

    $valid_extensions = array("jpg", "jpeg", "png");

    if (in_array(strtolower($imageFileType), $valid_extensions)) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
            echo json_encode(["success" => true, "picture" => $location]);
        } else echo json_encode(["success" => false, "error" => "L'image n'a pas pu être transférée"]);
    } else echo json_encode(["success" => false, "error" => "L'extension de l'image n'est pas acceptée"]);
} else echo json_encode(["success" => false, "error" => "Les données ne sont pas correctement renseignée"]);
