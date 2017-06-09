<?php
session_start();
if(isset($_GET['logout'])){
    session_destroy();
}
$error = "";
extract($_POST);
    if(isset($Email) && isset($Password) && !empty($Email) && !empty($Password)){
        $conn = mysql_connect('localhost','root','');
        if($conn){
            mysql_select_db('forum',$conn);
            $result = mysql_query("SELECT * FROM user WHERE Email='".$Email."'");
            $row = mysql_fetch_assoc($result);
            if(!$row){
                $error = '此账号不存在';
                mysql_close($conn);
            }else{
                $result = mysql_query("SELECT * FROM user WHERE Email='".$Email."'AND Password='".$Password."'");
                $row = mysql_fetch_assoc($result);
                if(!($row)){
                    $error = '密码错误！';
                    mysql_close($conn);
                }else{
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['Name'] = $row['Name'];
                    mysql_close($conn);
                }
            }
        }else{
            die('Could not connect: ' .mysql_errno()) ;
        }
    }else if((isset($Email) && empty($Email)) || (isset($Password) && empty($Password))){
        $error = "信箱及密码栏位请勿空白!";
    }
echo $error;