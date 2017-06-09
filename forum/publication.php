<?php
extract($_POST);
date_default_timezone_set("Asia/Shanghai");
$timestamp = date("Y-m-d H:i:s", time());
if (empty($article_id)) {
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    mysql_query("INSERT INTO article(author_id, title, content, created_time, last_update) VALUES ('$id', '$title', '$content', '$timestamp', '$timestamp')");
    mysql_close($conn);
    echo "insert";
} else {
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    mysql_query("UPDATE article SET title='$title', content = '$content', last_update='$timestamp' WHERE id = '$article_id'");
    mysql_close($conn);
    echo "update";
}


