<?php
include_once "../system/backend/config.php";
    session_start();
    if($_SESSION["isLoggedIn"] == "true"){
        $access = $_SESSION["access"];
        switch ($access){
            case "admin":
                header("location:admin/manage-account");
                exit();
                break;
            case "staff":
                header("location:staff/manage-report");
                exit();
                break;
            default:
                session_destroy();
                header("location:../index.php");
                exit();
        }
    }else{
        session_destroy();
        header("location:../index.php");
        exit();
    }
?>