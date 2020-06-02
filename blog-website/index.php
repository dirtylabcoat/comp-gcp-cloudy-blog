<?php
namespace HiQ\CompGCP\BlogDemo;

require 'vendor/autoload.php';

use Google\Cloud\Datastore\DatastoreClient;

$datastore = new DatastoreClient([
    'projectId' => 'compgcp-blog-demo',
    'namespaceId' => 'blog'
]);
$query = $datastore->query()
        ->kind('blogpost')
        ->order('created');

echo "<html>\n<head>\n<title>Cloudy Blog</title>\n</head>\n<body>\n<h1>Cloudy Blog</h1>\n";
$c = 0;
foreach ($datastore->runQuery($query) as $post) {
    echo "<div>\n";
    printf("<h2>%s</h2>\n", $post->subject);
    printf("<em>%s</em><br/>\n", $post->created->format('Y-m-d H:i:s'));
    printf("%s\n</div><hr/>\n", $post->body);
}
echo "<div><form action=\"new-subscriber.php\" method=\"POST\">\n";
echo "<input type=\"new-subscriber.php\" name=\"emailAddress\" id=\"emailAddress\"/>\n";
echo "&nbsp;<input type=\"submit\"/>\n";
echo "</form></div>\n";
echo "<div><a href=\"admin.php\">admin</a></div>\n";

echo"</body>\n</html>";
