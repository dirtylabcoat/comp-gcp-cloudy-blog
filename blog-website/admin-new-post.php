<?php
namespace HiQ\CompGCP\BlogDemo;

require 'vendor/autoload.php';

use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\PubSub\PubSubClient;

$datastore = new DatastoreClient([
    'projectId' => 'compgcp-blog-demo',
    'namespaceId' => 'blog'
]);
$pubSub = new PubSubClient();
// Save to Datastore
$post = $datastore->entity('blogpost');
$post['subject'] = $_POST['subject'];
$post['body'] = $_POST['body'];
$post['created'] = new \DateTime();
$datastore->insert($post);
// Publish message to Pub/Sub-topic
$topic = $pubSub->topic('new-blogpost');
$msg = "{\"subject\":\"".$_POST['subject']."\"}";
$topic->publish([
    'data' => $msg
]);
// Redirect back to blog
header("Location: /index.php");
die();
