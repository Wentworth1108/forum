<?php
extract($_POST);
date_default_timezone_set("Asia/Shanghai");
$timestamp = date("Y-m-d H:i:s", time());
$conn = mysql_connect('localhost', 'root', '');
mysql_select_db('forum', $conn);
mysql_query("INSERT INTO response(article_id, user_id, message, timestamp) VALUES ('$article_id', '$id', '$message', '$timestamp')");
mysql_query("UPDATE article SET last_update='$timestamp' WHERE id = '$article_id'");

$result = mysql_query("SELECT response.*, user.Name FROM response LEFT JOIN user ON response.user_id = user.id
                                      WHERE article_id='$article_id'ORDER BY response.timestamp  DESC");
$dataCount = mysql_num_rows($result);
for ($i = 0; $i < $dataCount; $i++) {
    $row[$i] = mysql_fetch_assoc($result);
}
mysql_close($conn);

echo json_encode($row);