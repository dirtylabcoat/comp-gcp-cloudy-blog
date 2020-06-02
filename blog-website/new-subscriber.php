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
$subscriber = $datastore->entity('subscriber');
$subscriber['emailAddress'] = $_POST['emailAddress'];
$subscriber['created'] = new \DateTime();
$datastore->insert($subscriber);
// Publish message to Pub/Sub-topic
$topic = $pubSub->topic('new-subscriber');
$msg = "{\"emailAddress\":\"".$_POST['emailAddress']."\"}";
$topic->publish([
    'data' => $msg
]);
// Redirect back to blog
header("Location: /index.php");
die();
