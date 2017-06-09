<?php
session_start();
extract($_POST);
$error = "";
if (isset($Name) && isset($Email) && isset($Password) && isset($ConfirmPassword) && !empty($Name) && !empty($Email) && !empty($Password) && !empty($ConfirmPassword)
    && !empty($Name) && !empty($Email) && !empty($Password) && !empty($ConfirmPassword)
) {
    $conn = mysql_connect('localhost', 'root', '');
    if ($conn) {
        mysql_select_db('forum', $conn);
        $result = mysql_query("SELECT * FROM user WHERE Email='" . $Email . "'");
        $row = mysql_fetch_assoc($result);
        if ($row) {
            $error = 'Warning!此账号已被注册   ';
            mysql_close($conn);
        } else {
            if ($Password != $ConfirmPassword) {
                $error = '确认密码栏位与密码栏不同';
                mysql_close($conn);
            } else {
                mysql_query("INSERT INTO user(Name, Email, Password) VALUES ('$Name', '$Email', '$Password')");
                $result = mysql_query("SELECT * FROM user WHERE Email='" . $Email . "'");
                $row = mysql_fetch_assoc($result);
                $_SESSION['id'] = $row['id'];
                $_SESSION['Name'] = $row['Name'];
                mysql_close($conn);
            }
        }
    } else {
        $error = '数据库连接失败！';
    }
} else if ((isset($Name) && empty($Name)) || (isset($Email) && empty($Email)) || (isset($Password) && empty($Password)) || (isset($ConfirmPassword) && empty($ConfirmPassword))) {
    $error = "Warning!请确保填写所有栏位";
}
echo $error;
