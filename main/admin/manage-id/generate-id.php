<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function checkId($id){
            global $conn;
            $table = "id";
            $sql = "SELECT idx FROM `$table` WHERE id='$id'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    return "true";
                }else{
                    return "false";
                }
            }else{
                return "System Error!";
            }
        }

        function generateId(){
            $check = "true";
            while($check != "false"){
                $id = generateCode(10);
                $check = checkId($id);
                if($check == "false"){
                    break;
                }else if($check == "true"){

                }else{
                    return $check;
                }
            }
            global $conn;
            $table = "id";
            $generatedBy = $_SESSION["loginidx"];
            $sql = "INSERT INTO `$table` (id, generatedby) VALUES ('$id', '$generatedBy')";
            if(mysqli_query($conn,$sql)){
                return "true*_*" . $id;
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true"){
            echo generateId();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>