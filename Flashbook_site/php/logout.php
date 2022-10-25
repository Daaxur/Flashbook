<?php
    session_start();
    $_SESSION['connected'] = false;
    unset($_SESSION['user_id']);

    echo json_encode(["success" => true]);
