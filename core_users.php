<?php
define('AWS_KEY', 'YOUR Key');
define('AWS_SECRET_KEY', 'YOUR Secret Key');

require 'aws.phar';
date_default_timezone_set('Asia/Kolkata');
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\DynamoDbClient;
$sdk = new Aws\Sdk([
    'region'   => 'ap-south-1',
    'version'  => 'latest',
    'http'    => [
        'verify' => 'C:\xampp\htdocs\chatbox\curl-ca-bundle.crt'
      ]
]);

$dynamodb = $sdk->createDynamoDb();

$tableName = 'Users';

$client = DynamoDbClient::factory([
    'region'  => 'ap-south-1',
    'version' => 'latest',
    'http'    => [
        'verify' => 'C:\xampp\htdocs\chatbox\curl-ca-bundle.crt'
      ]
]);
?>
