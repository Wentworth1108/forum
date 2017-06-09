WWW Technologies and Applications 2016
HOMEWORK 5
NAME : Wen Shuai
CCU ID: 404410907
Link : http://dmplus.cs.ccu.edu.tw/~s404410907/HW3/forum/signIn.php

Project files :signIn.html, signIn.php, sigIn.js, index.php, createAccount.php, publication.php, article.php, response.php, index.js
    [signIn.html]: This page is style of login and create new account.

    [signIn.php]: This page is a php file.It is mainly for query user information and output an error message of sign in.

    [createAccount.php]: This page is a php file.It is mainly for query user information and output an error message of create an account.

    [sigIn.js]: In this page I use jQuery to validate the forms, and use jQuery's post method to get the error message is output in the form below.

	[index.php]: I show the article list in the index page. Besides that, "create new article" button be provided to a user. When a user clicks it,
	the create page should be shown in the same page.

	[index.js]: In this page I implement a editor.The editor support at five different style buttons and YouTube insertion which can embed an YouTube video
	in the article. When a user is done with their article content, the user can select text and click the style button. The selected text will be marked
	with a tag. When the user want to insert a YouTube video, an input field is provided to the user. After the user inputs the YouTube URL and clicks the
	“insert” button, the video is added into the article. Moreover, two view modes which are “Code mode” and “HTML mode”be provided the user to preview
	their articles. On the other hand, When the user edits the article, I show both the content and the tags, so that the user can remove the style effect by
	deleting the tags. .

	[publication.php]: This page is used to update and insert article message to database.

	[article.php]: This page is used to select article message in database.

	[response.php]: This page is used to select and insert response message in database.

	How to implement the AJAX:
	[sigIn.js]:Use jQuery's post method to implement the page does not refresh but the data can be constantly updated.It make sure
    the the user think they stay in the same page when they post message.
    [index.js]:When the user click the “Edit article” button, I use AJAX to access the article data and shown the content to the user. Furthermore,When the
    user creates a new article or saves the edit change, the data should be wrapped into the JSON format and be submitted to database by also using AJAX.
    For the response part, I use AJAX to submit user´s response and also use AJAX to update the response list.

	List the files where JSON format are used:[sigIn.js]:In the third line, use jQuery ajax - serializeArray method to returns JSON data structure data.
	[response.php] [article.php]: In the last line, use json_encode method make select data to json format.
    [signIn.php]: In the 101, 150 line(In publishBtn and responseBtn click function), use jQuery ajax - serializeArray method to returns JSON data structure data.
    In 106, 120, 139, 150 line(In publishBtn and responseBtn click function), use JavaScript eval method JSON text will be converted to JavaScript objects.
(Additional functions you implement)
	No additional functions.