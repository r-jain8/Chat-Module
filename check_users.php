<?php
session_start();

require 'aws.phar';
date_default_timezone_set('UTC');
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\DynamoDbClient;
$tableName = 'Users';
$sdk = new Aws\Sdk([
    'region'   => 'ap-south-1',
    'version'  => 'latest',
    'http'    => [
        'verify' => 'C:\xampp\htdocs\chatbox\curl-ca-bundle.crt'
      ]
]);

$dynamodb = $sdk->createDynamoDb();

$client = DynamoDbClient::factory([
    'region'  => 'ap-south-1',
    'version' => 'latest',
    'http'    => [
        'verify' => 'C:\xampp\htdocs\chatbox\curl-ca-bundle.crt'
      ]
]);

$UserName = $_SESSION["username"];
$password = $_SESSION["password"];

$response = $client->getItem([
    'ConsistentRead' => true,
    'TableName' =>$tableName,
    'Key' => [
        'UserName'  => ['S' => $UserName],
        'UserPassword' => ['S' => $password]
    ],
  ]);
  $_SESSION["user"]=$response['Item']['UserName']['S'];
  $_SESSION["list"]=$response["Item"]["FriendList"]["NS"];
  $_SESSION["id"]=$response["Item"]["UserID"]["S"];

if($response['Item']['UserName']['S'] == '')
    {
    session_destroy();

    ?>
    <script language="javascript">
    window.location = 'login_users1.php';
    </script>
    <?php
  }
else{

  $resp = $dynamodb->updateItem([
    'TableName' => $tableName,
    'Key' => [
        'UserName' => [ 'S' => $UserName ],
        'UserPassword' => [ 'S' => $password]
    ],
    'ExpressionAttributeValues' =>  [
       ':val1' => ['N' => '1']
   ] ,
  'UpdateExpression' => 'SET OnlineStatus = :val1',
  'ReturnValues' => 'ALL_NEW'
]);
  ?>
  <script language="javascript">
  window.location = 'chat.php';
  </script>
  <?php
}
?>
