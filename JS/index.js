$(document).ready(function () {
    $("#code").click(function () {
        $("#content").show();
        $("#htmlContent").hide();
    });

    $("#html").click(function () {
        var content = $("#content");
        var htmlContent = $("#htmlContent");
        var text = content.val();

        content.hide();
        htmlContent.show();

        text = text.replace(/<R>/g, "<span style=\"color:red;\">");
        text = text.replace(/<\/R>/g, "</span>");
        text = text.replace(/<B>/g, "<span style=\"color:blue;\">");
        text = text.replace(/<\/B>/g, "</span>");
        text = text.replace(/<G>/g, "<span style=\"color:green;\">");
        text = text.replace(/<\/G>/g, "</span>");
        text = text.replace(/\n/g, "<br>");

        if (text.indexOf("YOUTUBE") != -1) {
            text = text.replace(/<YOUTUBE>/g, "( Video ID:");
            text = text.replace(/<\/YOUTUBE>/g, ")");
            htmlContent.html(text).prepend("<p>(影片不提供预览)</p>");
        } else {
            htmlContent.html(text);
        }
    });

    $("#video").click(function () {
        $("#insertVideo").toggle();
    });

    $("#insert").click(function () {
        var url =$("#url");
        var content = $("#content");
        var vaule =url .val();
        if (vaule != "") {
            var text =content .val();
            var newText = text + "\n<YOUTUBE>" + vaule + "</YOUTUBE>";
            content.val(newText);
        }
        $("#insertVideo").hide();
        url.val("");
    });

    $("#bold").click(function () {
        var content_js = document.getElementById("content");
        var content_jq = $("#content");
        var start = content_js.selectionStart;
        var end = content_js.selectionEnd;
        var value = content_jq.val();
        var newValue = value.substr(0, start) + "<Strong>" + value.substr(start, end - start) + "</Strong>" + value.substr(end);
        content_jq.val(newValue);
    });


    $("#italic").click(function () {
        var content_js = document.getElementById("content");
        var content_jq = $("#content");
        var start = content_js.selectionStart;
        var end = content_js.selectionEnd;
        var value = content_jq.val();
        var newValue = value.substr(0, start) + "<em>" + value.substr(start, end - start) + "</em>" + value.substr(end);
        content_jq.val(newValue);
    });

    $("#red").click(function () {
        var content_js = document.getElementById("content");
        var content_jq = $("#content");
        var start = content_js.selectionStart;
        var end = content_js.selectionEnd;
        var value = content_jq.val();
        var newValue = value.substr(0, start) + "<R>" + value.substr(start, end - start) + "</R>" + value.substr(end);
        content_jq.val(newValue);
    });

    $("#blue").click(function () {
        var content_js = document.getElementById("content");
        var content_jq = $("#content");
        var start = content_js.selectionStart;
        var end = content_js.selectionEnd;
        var value = content_jq.val();
        var newValue = value.substr(0, start) + "<B>" + value.substr(start, end - start) + "</B>" + value.substr(end);
        content_jq.val(newValue);
    });

    $("#green").click(function () {
        var content_js = document.getElementById("content");
        var content_jq = $("#content");
        var start = content_js.selectionStart;
        var end = content_js.selectionEnd;
        var value = content_jq.val();
        var newValue = value.substr(0, start) + "<G>" + value.substr(start, end - start) + "</G>" + value.substr(end);
        content_jq.val(newValue);
    });

    $("#publishBtn").click(function () {
        var data = $('#publishForm').serializeArray();
        if ($("#article_id").val() == "") {
            $.post("../forum/publication.php", data, function (flag) {
                if (flag == "insert") {
                    $("#content").val("");
                    $("#htmlContent").val("");
                    $("#title").val("");
                    $.post("../forum/article.php", {id: $("#userId").val()}, function (data) {
                        var articleData = eval('(' + data + ')');
                        var result = "";
                        for (var i = 0; i < articleData.length; i++) {
                            result = result + "<tr>\
								<td>" + articleData[i]['created_time'] + "</td>\
								<td>" + articleData[i]['Name'] + "</td>\
								<td><a href=\"../forum/index.php?article_id=" + articleData[i]['id'] + "\">" + articleData[i]['title'] + "</a></td>\
								<td>" + articleData[i]['count'] + "</td>\
								<td>" + articleData[i]['last_update'] + "</td>\
								  </tr>"
                        }
                        $("#selfArticleList").html(result);
                    });
                    $.post("../forum/article.php", function (data) {
                        var articleData = eval('(' + data + ')');
                        var result = "";
                        for (var i = 0; i < articleData.length; i++) {
                            result = result + "<tr>\
									<td>" + articleData[i]['created_time'] + "</td>\
									<td>" + articleData[i]['Name'] + "</td>\
									<td><a href=\"../forum/index.php?article_id=" + articleData[i]['id'] + "\">" + articleData[i]['title'] + "</a></td>\
									<td>" + articleData[i]['count'] + "</td>\
									<td>" + articleData[i]['last_update'] + "</td>\
								  </tr>"
                        }
                        $("#articleList").html(result);
                    });
                }
            });
        } else {
            $.post("../forum/publication.php", data, function (flag) {
                if (flag == "update") {
                    $.post("../forum/article.php", {article_id: $("#article_id").val()},function (data) {
                        var articleData = eval('(' + data + ')');
                        $("#articleTitle").html(articleData.title);
                        $("#articleUpdate").html(articleData.Name + "<span style=\"font-style: italic\"> Updated On " + articleData.last_update + "</span>");
                        $("#articleContent").html($.transform(articleData.content));
                    });
                }
            });
        }
    });

    $("#responseBtn").click(function () {
        var data = $('#responseForm').serializeArray();
        $.post("../forum/response.php", data, function (data) {
            var response = eval('(' + data + ')');
            var result = "";
            for (var i = 0; i < response.length; i++) {
                result = result + "<tr><td>" + response[i].Name + "</td><td>" + response[i].message + "</td><td>" + response[i].timestamp + "</td></tr>";
            }
            $("#responseList").html(result);
        });
        $("#comment").val("");
    });
});

$.extend({
    transform : function (text) {
    text = text.replace(/<R>/g, "<span style=\"color:red;\">");
    text = text.replace(/<\/R>/g, "</span>");
    text = text.replace(/<B>/g, "<span style=\"color:blue;\">");
    text = text.replace(/<\/B>/g, "</span>");
    text = text.replace(/<G>/g, "<span style=\"color:green;\">");
    text = text.replace(/<\/G>/g, "</span>");
    text = text.replace(/<YOUTUBE>/g,"<iframe src=\"https://www.youtube.com/v/");
    text = text.replace(/<\/YOUTUBE>/g,"\" width=\"550\" height=\"400\" frameborder=\"0\" allowfullscreen></iframe>");
    text = text.replace(/\n/g, "<br>");
    return text;
    }
});