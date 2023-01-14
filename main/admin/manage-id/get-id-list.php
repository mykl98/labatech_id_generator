<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getAccountName($idx){
            global $conn;
            $name = "";
            $table = "account";
            $sql = "SELECT name FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $name = $row["name"];
                }
            }
            return $name;
        }

        function getIdList(){
            global $conn;
            $data = array();
            $table = "id";
            $sql = "SELECT * FROM $table";
            if($result=mysqli_query($conn,$sql)){
                while($row=mysqli_fetch_array($result)){
                    $value = new \StdClass();
                    $value -> idx = $row["idx"];
                    $value -> id = $row["id"];
                    $value -> generatedby = getAccountName($row["generatedby"]);
                    array_push($data,$value);
                }
                $data = json_encode($data);
                return "true*_*" . $data;
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true"){
            echo getIdList();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>