<?php
extract($_POST);
if (empty($article_id) && !empty($id)) {
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    $result = mysql_query("SELECT article.*, user.Name, COUNT(response.id) AS count FROM article INNER JOIN user ON article.author_id = user.id LEFT JOIN response ON article.id = response.article_id  WHERE user.id = '$id' GROUP BY article.id ORDER BY article.last_update DESC");
    $dataCount = mysql_num_rows($result);
    for ($i = 0; $i < min($dataCount, 5); $i++) {
        $row[] = mysql_fetch_assoc($result);
    }
    mysql_close($conn);
} else if (!isset($_POST['article_id']) && !isset($_POST['id'])) {
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    $result = mysql_query("SELECT article.*, user.Name, COUNT(response.id) AS count FROM article
INNER JOIN user ON article.author_id = user.id LEFT JOIN response ON article.id = response.article_id
GROUP BY article.id ORDER BY article.last_update DESC");
    $dataCount = mysql_num_rows($result);
    mysql_data_seek($result, 0);
    for ($i = 0; $i < $dataCount; $i++) {
        $row[] = mysql_fetch_assoc($result);
    }
    mysql_close($conn);
} else if (isset($_POST['article_id'])) {
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    $result = mysql_query("SELECT article.*, user.Name FROM article INNER JOIN user ON user.id= article.author_id AND article.id = $article_id");
    $row = mysql_fetch_assoc($result);
}

echo json_encode($row);