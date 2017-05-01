<html>
<head>
  <title>User FriendList</title>
  <meta name="author" content="Raunak Jain">
</head>
</html>
<?php
include 'core_users.php';
session_start ();

echo 'Hello ';
echo $_SESSION["user"];

echo "<br>";

echo 'Here are your friends. Select the name of the friend, with whom you want to chat - ';
echo "<br>";

$FriendList = (array)$_SESSION["list"];
//print_r($FriendList);

$result = $dynamodb->scan([
	'TableName' => $tableName
]);

foreach ($result['Items'] as $item) {
	$value = $item['UserName']['S'];
	$id = $item['UserID']['S'];
	if(in_array($id, $FriendList)){

		 ?>
		 <form action="chatindex.php" method="post">
		   <button type="submit" name="fid" value="<?php echo "$id";?>"><?php  echo $value ?></button>
	   </form>
		 <?php
	 }
 }

?>
<form action="login_users.php" method="post">
	<button type="submit" name="logout" value="logout">Logout</button>
</form>
