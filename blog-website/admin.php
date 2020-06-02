<?php
echo "<html>\n<head>\n<title>Cloudy Blog :: Admin</title>\n</head>\n<body>\n<h1>Cloudy Blog :: Admin</h1>\n";
echo "<div><form action=\"admin-new-post.php\" method=\"POST\">\n";
echo "Subject: <input type=\"text\" id=\"subject\" name=\"subject\" /><br/>";
echo "Body:<br/><textarea rows=\"10\" cols=\"40\" id=\"body\" name=\"body\"></textarea><br/>";
echo "<input type=\"submit\"/>";
echo "</form></div>\n";
echo "<div><a href=\"index.php\">back</a></div>\n";
echo "</body></html>";
