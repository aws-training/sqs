<?php 
require '/var/www/html/vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;

// Use the default credential provider
$provider = CredentialProvider::defaultProvider();
var_dump($provider);
$client = new SqsClient([
    'profile' => 'default',
    'region' => 'ap-south-1',
    'version' => '2012-11-05',
'credentials' => $provider
]);
$result = $client ->getQueueUrl(array('QueueName' => "myqueue"));
    $queue_url = $result->get('QueueUrl');
echo "The queue URL is .... $queue_url";
$params = [
    'DelaySeconds' => 10,
    'MessageAttributes' => [
        "Title" => [
            'DataType' => "String",
            'StringValue' => "The Hitchhiker's Guide to the Galaxy"
        ],
	"Author" => [
            'DataType' => "String",
            'StringValue' => "Douglas Adams."
        ],
	"WeeksOn" => [
            'DataType' => "Number",
            'StringValue' => "6"
        ]
    ],
    'MessageBody' => "Information about current NY Times fiction bestseller for week of 12/11/2016.",
    'QueueUrl' => $queue_url
];

try {
    $result = $client->sendMessage($params);
    var_dump($result);
} catch (AwsException $e) {
    // output error message if fails
    error_log($e->getMessage());
}
