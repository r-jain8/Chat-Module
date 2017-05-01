<?php
include 'core_users.php';
session_start();
date_default_timezone_set('Asia/Kolkata');

if(isset($_SESSION['user'])){
    if($_SESSION['id']<$_SESSION['fid']){
    $file = $_SESSION['id']."_".$_SESSION['fid'];
    }
    else{
      $file = $_SESSION['fid']."_".$_SESSION['id'];
    }
      if(isset($_POST['key']))
      {
          $code = $_POST['key'];

$removeitem = $dynamodb->query([
 'TableName' => 'SelectionCart',
 'IndexName' => 'UsersId-ProductCode-index',
 'KeyConditionExpression' => '#dt = :v_dt and #ut = :u_dt',
 'ExpressionAttributeNames' => [
          '#dt' => 'UsersId',
          '#ut' => 'ProductCode',
        ],
     'ExpressionAttributeValues' =>  [
         ':v_dt' => ['S' => $file],
         ':u_dt' => ['S' => $code]
     ],
     'Select' => 'ALL_ATTRIBUTES'
 ]);

foreach ($removeitem['Items'] as $item) {
   $sortkey = $item['DateAndTime']['S'];
   $cartitem = $dynamodb->deleteItem ( [
    'TableName' => 'SelectionCart',
    'Key' => [
        'UsersId'  => ['S' => $file],
        'DateAndTime' => ['S' => $sortkey]
    ]
   ]);
   break;
 }
}
}
?>
