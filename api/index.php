<?php
if($_POST){
    include_once "../system/backend/config.php";

    function checkId($id){
        global $conn;
        $table = "machine";
        $sql = "SELECT idx FROM `$table` WHERE washer='$id'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "true";
            }else{
                $sql = "SELECT idx FROM `$table` WHERE dryer='$id'";
                if($result=mysqli_query($conn,$sql)){
                    if(mysqli_num_rows($result) > 0){
                        return "true";
                    }else{
                        return "false";
                    }
                }else{
                    return "false";
                }
            }
        }else{
            return "false";
        }
    }

    function checkCard($card){
        global $conn;
        $table = "account";
        $sql = "SELECT idx FROM `$table` WHERE card='$card'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "true";
            }else{
                return "false";
            }
        }else{
            return "false";
        }
    }

    function processTransaction($id,$card){
        $check = checkId($id);
        if($check != "true"){
            return $check;
        }
        $check = checkCard($card);
        if($check != "true"){
            return $check;
        }

        global $conn;
        $date = date("Y-m-d");
        $time = date("h:i:s a");
        $table = "transaction";
        $sql = "INSER INTO `$table` (date,time,id,card,cancelled) VALUES ('$date','$time','$id','$card','')";
        if(mysqli_query($conn,$sql)){
            return "true";
        }else{
            return "false";
        }
    }

    $id = sanitize($_POST["id"]);
    $card = sanitize($_POST["card"]);
    if(!empty($id) && !empty($card)){
        processTransaction($id,$card);
    }else{
        return "false";
    }
    
}else{
    echo "Access Denied!";
}
?>