<?php
session_start();
$session = session_id();
$sessionId = $_SESSION['id'];
if (empty($_SESSION['id'])) {
    header("Location:signIn.html");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Article</title>
    <link type="text/css" rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>
                Mr.Wen
                Forum</a>
        </div>

        <div class="collapse navbar-collapse navbar-right" style="margin-right: 20px; font-size: 18px">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="signIn.html?logout">Logout</a></li>
            </ul>
        </div>
    </nav>
    <?php echo "<input id=\"userId\" value=\"".$_SESSION['id']."\" hidden>"; ?>
    <h4 style="margin-top: 80px; font-style: italic">Welcome back, <?php echo $_SESSION['Name'] ?>!</h4>
</div>
<?php
if (empty($_GET['article_id'])) {
    echo "
<div class=\"container-fluid\">
    <div class=\"row\">
        <div class=\"content col-md-12 col-sm-12\">
            <div class=\"table-responsive\">
                <input id='article_id' hidden>
                <a class=\"btn btn-primary  pull-right\" data-toggle=\"modal\" data-target=\"#publish\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span> 发表新主题</a>
                <a><h3><label><span class=\"glyphicon glyphicon-flash\" aria-hidden=\"true\"></span> 您最近更新/被留言的文章</label></h3></a>
                <table class=\"table table-striped table-hover\">
                    <thead>
                    <tr>
                        <th>发表日期</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>回复</th>
                        <th>最近更新/回复</th>
                    </tr>
                    </thead>
                    <tbody id='selfArticleList'>";
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    $result = mysql_query("SELECT article.*, user.Name, COUNT(response.id) AS count FROM article
INNER JOIN user ON article.author_id = user.id LEFT JOIN response ON article.id = response.article_id  WHERE user.id = '" . $_SESSION['id'] . "'
GROUP BY article.id ORDER BY article.last_update DESC");
    $dataCount = mysql_num_rows($result);
    for ($i = 0; $i < min($dataCount, 5); $i++) {
        $row = mysql_fetch_assoc($result);
        echo "
            <tr>
                <td>" . $row['created_time'] . "</td>
                <td>" . $row['Name'] . "</td>
                <td><a href=\"index.php?article_id=" . $row['id'] . "\">" . $row['title'] . "</a></td>
                <td>" . $row['count'] . "</td>
                <td>" . $row['last_update'] . "</td>
           </tr> ";
    }
    echo "
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class=\"row\">
        <div class=\"content col-md-12 col-sm-12\">
            <div class=\"table-responsive\">
                <a><h3><label><span class=\"glyphicon glyphicon-th-list\" aria-hidden=\"true\"></span> 所有文章列表</label></h3>
                </a>
                <table class=\"table table-striped table-hover\">
                    <thead>
                    <tr>
                        <th>发表日期</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>回复</th>
                        <th>最近更新/回复</th>
                    </tr>
                    </thead>
                    <tbody id='articleList'>";
    $dataCount = mysql_num_rows($result);
    $result = mysql_query("SELECT article.*, user.Name, COUNT(response.id) AS count FROM article
INNER JOIN user ON article.author_id = user.id LEFT JOIN response ON article.id = response.article_id
GROUP BY article.id ORDER BY article.last_update DESC");
    $dataCount = mysql_num_rows($result);
    mysql_data_seek($result, 0);
    for ($i = 0; $i < $dataCount; $i++) {
        $row = mysql_fetch_assoc($result);
        echo "
            <tr>
                <td>" . $row['created_time'] . "</td>
                <td>" . $row['Name'] . "</td>
                <td><a href='index.php?article_id=" . $row['id'] . "'>" . $row['title'] . "</a></td>
                <td>" . $row['count'] . "</td>
                <td>" . $row['last_update'] . "</td>
           </tr> ";
    }
    echo "
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>";
    mysql_close($conn);
} else {
    $_GET['article_id'] = intval($_GET['article_id']);
    if (empty($_GET['delete_id'])) {
        $conn = mysql_connect('localhost', 'root', '');
        mysql_select_db('forum', $conn);
        $result = mysql_query("SELECT article.*, user.Name FROM article INNER JOIN user ON user.id= article.author_id AND article.id = '" . $_GET['article_id'] . "'");
        $row = mysql_fetch_assoc($result);
        echo "
<div class=\"container-fluid\">
    <div class=\"container-fluid\">
        <div class=\"row\">
            <h3 id='articleTitle'>" . $row['title'] . "</h3>
            <h4><div id='articleUpdate'>" . $row['Name'] . "<span style=\"font-style: italic\"> Updated On " . $row['last_update'] . "</span></div>";
        if ($_SESSION['id'] == $row['author_id']) {
            echo "
                    <div class=\"pull-right\">
                           <input id='article_id' value=" . $row['id'] . " hidden>
                           <a id='edit' class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#publish\">
                                <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a>
                            <a class=\"btn btn-danger\" href='index.php?article_id=" . $row['id'] . "&delete_id=" . $session . "'>
                                <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Delete</a>
                    </div>";
        }
        $row['content'] = str_replace("<R>","<span style=\"color:red;\">",$row['content']);
        $row['content'] = str_replace("</R>","</span>",$row['content']);
        $row['content'] = str_replace("<B>","<span style=\"color:blue;\">",$row['content']);
        $row['content'] = str_replace("</B>","</span>",$row['content']);
        $row['content'] = str_replace("<G>","<span style=\"color:green;\">",$row['content']);
        $row['content'] = str_replace("</G>","</span>",$row['content']);
        $row['content'] = str_replace("<YOUTUBE>","<iframe src=\"https://www.youtube.com/v/",$row['content']);
        $row['content'] = str_replace("</YOUTUBE>","\" width=\"550\" height=\"450\" frameborder=\"0\" allowfullscreen></iframe>",$row['content']);
        $row['content'] = str_replace("\n","<br>",$row['content']);
        echo "
            </h4>
        </div>
    </div>
</div>
            <hr/>
            <div class=\"container-fluid\">
                <div class=\"row\">
                    <div class=\"col-md-12\">
                        <p id='articleContent'>" . $row['content'] . "</p>
                    </div>
                </div>
            </div>
<div class=\"container-fluid\">
    <div class=\"row\" style=\"margin-top: 5%\">
        <div class=\"col-md-12\">
            <form class=\"form-inline\" id='responseForm'>
                <h4><span class=\"glyphicon glyphicon-comment\" aria-hidden=\"true\"></span> Response</h4>
                <label for=\"comment\">" . $_SESSION['Name'] . "</label>
                <input type=\"hidden\" name=\"article_id\" value='" . $_GET['article_id'] . "'>
                <input name=\"id\" value=\"".$_SESSION['id']."\" hidden>
                <input type=\"text\" id=\"comment\" class=\"form-control\" name=\"message\"
                       placeholder=\"Give a comment to this article...\" style=\"width:25%;\">
                <button id='responseBtn' type=\"button\" class=\"col-lg-pull-7 btn btn-primary\">Submit</button>
            </form>
        </div>
    </div>
</div>
<div class=\"container-fluid\">
    <div class=\"row\">
        <div class=\"col-md-12\">
            <div class=\"table-responsive\">
                <p>
                <table class=\"table table-striped table-hover\">
                 <thead>
                    <tr>
                        <th>Name</th>
                        <th>Comment</th>
                        <th>Last Updated</th>
                    </tr>
                    </thead>
                    <tbody  id='responseList'>";
        $result = mysql_query("SELECT response.*, user.Name FROM response LEFT JOIN user ON response.user_id = user.id
                                      WHERE article_id='" . $_GET['article_id'] . "'ORDER BY response.timestamp  DESC");
        $dataCount = mysql_num_rows($result);
        for ($i = 0; $i < $dataCount; $i++) {
            $row = mysql_fetch_assoc($result);
            echo "
            <tr>
                <td>" . $row['Name'] . "</td>
				<td>" . $row['message'] . "</td>
				<td>" . $row['timestamp'] . "</td>
           </tr> ";
        }
        mysql_close($conn);
        echo "
                    </tbody>
                </table>
                </p>
            </div>
        </div>
    </div>
</div>";
    } else if (!empty($_GET['delete_id']) && $_GET['delete_id'] == $session) {
        $conn = mysql_connect('localhost', 'root', '');
        mysql_select_db('forum', $conn);
        $delete_id = intval($_GET['article_id']);
        mysql_query("DELETE FROM article WHERE id = $delete_id");
        mysql_query("DELETE FROM response WHERE article_id = $delete_id");
        mysql_close($conn);
        if (mysql_errno()) {
            die("Fail to delete user with id $delete_id");
        } else {
            header("Location:index.php");
        }
    }  else {
        $article_id = intval($_GET['article_id']);
        header("Location:index.php?article_id=$article_id");
    }
}
?>
<div style="margin-top: 5%">
    <hr/>
    <footer>
        <p style="text-align: center">&copy;Wen Shuai 2016</p>
    </footer>
</div>
<?php
if (!empty($_GET['article_id'])) {
    $_GET['article_id'] = intval($_GET['article_id']);
    $article_id = $_GET['article_id'];
    $conn = mysql_connect('localhost', 'root', '');
    mysql_select_db('forum', $conn);
    $result = mysql_query("SELECT  * FROM article WHERE id = '" . $_GET['article_id'] . "'");
    $row = mysql_fetch_assoc($result);
    $title = $row['title'];
    $content = $row['content'];
    $author_id = $row['author_id'];
    mysql_close($conn);
} ?>

<div class="modal fade" id="publish" tabindex="-1" role="dialog" aria-labelledby="publishLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 id="modalTitle" style="text-align: center"><span class="glyphicon glyphicon-edit"
                                                                     aria-hidden="true"></span> <?php
                    if (empty($_GET['article_id'])) echo "发表新文章"; else echo "修改文章"; ?></h4>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="post" class="" id="publishForm">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label for="title" class="control-label" placeholder="Title">标题</label>
                                    </div>
                                    <div class="col-md-11">
                                        <input type="text" placeholder="User Name" class="form-control" id="title"
                                               name="title" <?php
                                        if (!empty($_GET['article_id'])) echo "value='$title'"; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col-md-1">
                                        <label for="content" class="control-label">内容:</label>
                                    </div>
                                    <div class="col-md-11" role="group">
                                        <div class="btn-group">
                                            <button type="button" id="code" class="btn btn-default">Code</button>
                                            <button type="button" id="html" class="btn btn-default">Html</button>
                                        </div>
                                        <div class="btn-group btn-group-sm" style="margin-left: 15px">
                                            <button type="button" id="video" class="btn btn-default"><span
                                                    class="glyphicon glyphicon-facetime-video"
                                                    aria-hidden="true"></span></button>
                                            <button type="button" id="bold" class="btn btn-default"><span
                                                    class="glyphicon glyphicon-bold" aria-hidden="true"></span></button>
                                            <button type="button" id="italic" class="btn btn-default"><span
                                                    class="glyphicon glyphicon-italic" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" id="red" class="btn btn-danger"><span
                                                    class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                            <button type="button" id="blue" class="btn btn-primary"><span
                                                    class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                            <button type="button" id="green" class="btn btn-success"><span
                                                    class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group" id="insertVideo" hidden>
                                    <div class="col-md-offset-1 col-md-11">
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="url">嵌入 YouTube 影片</label>
                                                <div class="input-group">
                                                    <span
                                                        class="input-group-addon">https://www.youtube.com/watch?v=</span>
                                                    <input type="text" class="form-control" id="url"
                                                           placeholder="video ID">
                                                </div>
                                            </div>
                                            <button id="insert" type="button" class="btn btn-default">Insert</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-11">
                                        <textarea class="form-control" rows="10" placeholder="Content" id="content"
                                                  name="content"><?php if(!empty($_GET['article_id'])) echo "$content"; ?></textarea>
                                        <div id="htmlContent"></div>
                                        <?php echo "<input name=\"id\" value=\"".$_SESSION['id']."\" hidden>";?>
                                        <input name="article_id" hidden <?php if(!empty($_GET['article_id'])) echo "value='".$_GET['article_id']."'"; ?>>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>
                <button type="button" id="publishBtn" class="btn btn-primary" data-dismiss="modal"><?php
                    if (empty($_GET['article_id'])) echo "Submit"; else echo "Save"; ?></button>
            </div>
        </div>
    </div>
</div>

<script src="../JS/jquery-2.1.4.min.js"></script>
<script src="../lib/bootstrap/js/bootstrap.min.js"></script>
<script src="../JS/index.js"></script>
</body>
</html>
