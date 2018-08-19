<?php
require '/var/www/html/vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;
try {
    // Use the default credential provider
$provider = CredentialProvider::defaultProvider();
$client = new SqsClient([    'profile' => 'default',    'region' => 'ap-south-1',    'version' => '2012-11-05','credentials' => $provider]);
    // Get the queue URL from the queue name.
    $result = $client->getQueueUrl(array('QueueName' => "myqueue"));
    $queue_url = $result->get('QueueUrl');


    // Receive a message from the queue
    $result = $client->receiveMessage(array(
        'QueueUrl' => $queue_url
    ));

    if ($result['Messages'] == null) {
        echo(" No message to process");
        exit;
    }

    // Get the message information

    $result_message = array_pop($result['Messages']);
    $queue_handle = $result_message['ReceiptHandle'];
    $message_json = $result_message['Body'];

var_dump($message_json);

echo "Deleting messages....";
$client->deleteMessage(array(
         'QueueUrl' => $queue_url,
         'ReceiptHandle' => $queue_handle
));
    echo "Deleted messages successfully....";

} catch (Exception $e) {
    die('Error receiving message from queue ' . $e->getMessage());

}
