<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getTypeName($type){
            if($type == "001"){
                return "Request for ambulance";
            }else if($type == "002"){
                return "Fire incident report";
            }else if($type == "003"){
                return "Request for police assistance";
            }else if($type == "004"){
                return "Request for road side repair assistance";
            }
        }

        function getStatusName($type){
            if($type == "001"){
                return "Waiting";
            }else if($type == "002"){
                return "Despatched";
            }else if($type == "003"){
                return "Completed";
            }
        }

        function getReportDetail($idx){
            global $conn;
            $data = array();
            $table = "report";
            $sql = "SELECT * FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $value = new \StdClass();
                    $value -> idx = $row["idx"];
                    $value -> date = $row["date"];
                    $value -> time = $row["time"];
                    $value -> number = $row["number"];
                    $value -> lat = $row["lat"];
                    $value -> lng = $row["lng"];
                    $value -> type = getTypeName($row["type"]);
                    $value -> status = getStatusName($row["status"]);
                    $value -> detail = $row["detail"];
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
            $idx = sanitize($_POST["idx"]);
            echo getReportDetail($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>