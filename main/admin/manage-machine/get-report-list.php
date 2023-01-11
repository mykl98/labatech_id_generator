<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getReportList($filter){
            global $conn;
            $data = array();
            $table = "report";
            if($filter == "all"){
                $sql = "SELECT * FROM `$table` ORDER BY idx DESC LIMIT 1000";
            }else{
                $sql = "SELECT * FROM `$table` WHERE status='$filter' ORDER BY idx LIMIT 1000";
            }
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $value = new \StdClass();
                        $value -> idx = $row["idx"];
                        $value -> date = $row["date"];
                        $value -> time = $row["time"];
                        $value -> number = $row["number"];
                        $value -> type = $row["type"];
                        $value -> lat = $row["lat"];
                        $value -> lng = $row["lng"];
                        $value -> status = $row["status"];
                        array_push($data,$value);
                    }
                }
                $data = json_encode($data);
                return "true*_*" . $data;
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true"){
            $filter = sanitize($_POST["filter"]);
            echo getReportList($filter);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>