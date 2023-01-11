<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getNumber($idx){
            global $conn;
            $table = "report";
            $number = "";
            $sql = "SELECT number FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $number = $row["number"];
                }
            }
            return $number;
        }

        function sendMessage($number, $message){
            global $conn;
            $table = "gateway";
            $sql = "INSERT INTO `$table` (number,message) VALUES ('$number','$message')";
            if(mysqli_query($conn,$sql)){
                return "true";
            }else{
                return "System Error!";
            }
        }

        function despatchReport($idx){
            global $conn;
            $data = array();
            $table = "report";
            $sql = "UPDATE `$table` SET status='002' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                $number = getNumber($idx);
                if($number == ""){
                    return "System Error!";
                }
                $send = sendMessage($number, "[Tuplok]A responder(s) are despatched to your location.");
                if($send != "true"){
                    return $send;
                }
                return "true*_*";
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true"){
            $idx = sanitize($_POST["idx"]);
            echo despatchReport($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>