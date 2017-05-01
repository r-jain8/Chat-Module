<?php
include 'core_users.php';
session_start();
date_default_timezone_set('Asia/Kolkata');
if(isset($_SESSION['user'])){
    $text = $_POST['text'];
    if($_SESSION['id']<$_SESSION['fid']){
    $file = $_SESSION['id']."_".$_SESSION['fid'];
    }
    else{
      $file = $_SESSION['fid']."_".$_SESSION['id'];
    }
    $response = $client->putItem([
        'TableName' => 'Chats',
        'Item' => [
            'UsersId'   => ['S' => $file],
            'DateAndTime'  => ['S' => date("d/m/Y H:i:s") . substr((string)microtime(), 1, 3)],
            'Sender' => ['S' => $_SESSION['user']],
            'Message' => ['S' => $text]
        ]
    ]);

    $pieces = explode(" ", $text);
    $size = count($pieces);
    for($i=0;$i<$size;$i++){
      if(substr($pieces[$i],0,1) == "@"){
        $res =substr($pieces[$i],1);


        $response = $client->getItem([
            'ConsistentRead' => true,
            'TableName' =>'Products',
            'Key' => [
                'ProductCode'  => ['S' => $res]
            ],
          ]);
          if($response['Item']['ProductLink']['S']!=''){
          $link = $response['Item']['ProductLink']['S'];
          $image = $response['Item']['ProductThumbnail']['S'];
          $name = $response['Item']['ProductName']['S'];
          $code = $response['Item']['ProductCode']['S'];


          $cartitem = $client->putItem([
              'TableName' => 'SelectionCart',
              'Item' => [
                  'UsersId'   => ['S' => $file ],
                  'DateAndTime'  => ['S' => date("d/m/Y H:i:s") . substr((string)microtime(), 1, 3)],
                  'ProductCode' => ['S' => $code],
                  'ProductName' => ['S' => $name],
                  'ProductLink' => ['S' => $link],
                  'ProductThumbnail' => ['S' => $image],
              ]
          ]);
          break;
        }
     }
  }
}
?>
