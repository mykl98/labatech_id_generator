<?php
if($_POST){
    include_once "../../../system/backend/config.php";

    function getOldUsername($idx){
        global $conn;
        $username = "";
        $table = "account";
        $sql = "SELECT username FROM `$table` WHERE idx='$idx'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $username = $row["username"];
            }
        }
        return $username;
    }

    function checkUsername($username){
        global $conn;
        $table = "account";
        $sql = "SELECT idx FROM `$table` WHERE username='$username'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "Username already exist. Please choose another username!";
            }else{
                return "true";
            }
        }else{  
            return "System Error!";
        }
    }

    function checkCard($card){
        global $conn;
        $table = "account";
        $sql = "SELECT idx FROM `$table` WHERE card='$card'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "This RFID card is already been used. Please use another card.";
            }else{
                return "true";
            }
        }else{
            return "System Error!";
        }
    }

    function getOldCard($idx){
        global $conn;
        $card = "";
        $table = "account";
        $sql = "SELECT card FROM `$table` WHERE idx='$idx'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $card = $row["card"];
            }
        }
        return $card;
    }

    function saveAccount($idx,$card,$name,$username,$access,$status){
        global $conn;
        $table = "account";
        if($idx == ""){
            $check = checkCard($card);
            if($check != "true"){
                return $check;
            }
            $check = checkUsername($username);
            if($check != "true"){
                return $check;
            }
            $sql = "INSERT INTO `$table` (card,name,username,password,access,status) VALUES ('$card','$name','$username','123456','$access','$status')";
            if(mysqli_query($conn,$sql)){
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }else{
            $old = getOldCard($idx);
            if($old == ""){
                return "System Error!";
            }
            if($old != $card){
                $check = checkCard($card);
                if($check != "true"){
                    return $check;
                }
            }
            $old = getOldUsername($idx);
            if($old == ""){
                return "System Error!";
            }
            if($old != $username){
                $check = checkUsername($username);
                if($check != "true"){
                    return $check;
                }
            }
            $sql = "UPDATE `$table` SET card='$card', name='$name',username='$username',access='$access',status='$status' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }
    }

    session_start();
    if($_SESSION["isLoggedIn"] == "true"){
        $idx = sanitize($_POST["idx"]);
        $card = sanitize($_POST["card"]);
        $name = sanitize($_POST["name"]);
        $username = sanitize($_POST["username"]);
        $access = sanitize($_POST["access"]);
        $status = sanitize($_POST["status"]);
        if(!empty($card)&&!empty($name)&&!empty($username)&&!empty($access)&&!empty($status)){
            echo saveAccount($idx,$card,$name,$username,$access,$status);
        }else{
            echo "Required fields are empty!";
        }
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>