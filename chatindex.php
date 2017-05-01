<?php
include 'core_users.php';
session_start ();
date_default_timezone_set('Asia/Kolkata');
?>
<script src="jquery-3.1.1.js"></script>
<script language="javascript" type="text/javascript">
var wsUri = "ws://localhost:9000/demo/server.php";
websocket = new WebSocket(wsUri);
websocket.onopen = function(ev) {
		alert('Connected to server ');
};
websocket.onclose = function(ev) {
		alert('Disconnected');
};
websocket.onerror = function(ev) {
		alert('Error Occurred');
};
</script>
<?php
if (isset ( $_POST ['fid'] )) {
		$_SESSION ['fid'] = stripslashes ( htmlspecialchars ( $_POST ['fid'] ) );
		if($_SESSION['id']<$_SESSION['fid']){
		$file = $_SESSION['id']."_".$_SESSION['fid'];
		}
		else{
		  $file = $_SESSION['fid']."_".$_SESSION['id'];
		}

		$read = "User " . $_SESSION ['user'] . " is reading the messages.";
		$response = $client->putItem([
				'TableName' => 'Chats',
				'Item' => [
						'UsersId'   => ['S' => $file ],
						'DateAndTime'  => ['S' => date("d/m/Y H:i:s") . substr((string)microtime(), 1, 3)],
						'Sender' => ['S' => '     '],
						'Message' => ['S' => $read]
				]
		]);
}

if (isset ( $_GET ['logout'] )) {

	if($_SESSION['id']<$_SESSION['fid']){
	$file = $_SESSION['id']."_".$_SESSION['fid'];
	}
	else{
		$file = $_SESSION['fid']."_".$_SESSION['id'];
	}
	$left = "User " . $_SESSION ['user'] . " is no longer reading the messages.";
	$response = $client->putItem([
			'TableName' => 'Chats',
			'Item' => [
					'UsersId'   => ['S' => $file],
					'DateAndTime'  => ['S' => date("d/m/Y H:i:s") . substr((string)microtime(), 1, 3)],
					'Sender' => ['S' => '     '],
					'Message' => ['S' => $left]
			]
	]);
	unset($_SESSION['fid']);
	unset($_SESSION['fname']);
	?>
	<script language="javascript" type="text/javascript">
	 websocket.close();
	 </script>
	 <?php
	header ( "Location: chat.php" ); // Redirect the user
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
body {
	font: 12px arial;
	color: #222;
	text-align: center;
	padding: 35px;
}

form,p {
	margin: 0;
	padding: 0;
}

input {
	font: 12px arial;
}

a {
	color: #0000FF;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

#wrapper {
	margin: 0 auto;
	padding-bottom: 25px;
	background: #EBF4FB;
	width: 504px;
	border: 1px solid #ACD8F0;
}


#chatbox {
	text-align: left;
	margin: 0 auto;
	margin-bottom: 25px;
	padding: 10px;
	background: #fff;
	height: 270px;
	width: 430px;
	border: 1px solid #ACD8F0;
	overflow: auto;
}
/* Dropdown Button */
.dropbtn {
    background-color: #D3F5B8;
    color: black;
		font-weight: bold;
    padding: 10px;
    font-size: 12px;
    cursor: pointer;
		border: #79b946 2px solid;
		margin-bottom:30px;
		width: 504px;
		margin: 0 auto;
}

/* Dropdown button on hover & focus */
.dropbtn:hover, .dropbtn:focus {
    background-color: #3e8e41;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 504px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
		height: 270px;
		overflow: auto;
}

/* Links inside the dropdown */
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
.show {display:block;}

#usermsg {
	width: 395px;
	border: 1px solid #ACD8F0;
}

#submit {
	width: 60px;
}

.error {
	color: #ff0000;
}
#menu {
	padding: 12.5px 25px 12.5px 25px;
}
.msgln{
	margin: 0 0 2px 0;
}
.welcome {
	float: left;
}

.logout {
	float: right;
}
</style>
<title>Chat Module</title>
</head>
<body>
	<?php
if (isset ( $_SESSION ['fid'] )) {
		    $fid=$_SESSION['fid'];
		    $response = $dynamodb->query([
		    'TableName' => $tableName,
		    'IndexName' => 'UserID-index',
		    'KeyConditionExpression' => '#dt = :v_dt',
		    'ExpressionAttributeNames' => ['#dt' => 'UserID'],
		    'ExpressionAttributeValues' => [
		        ':v_dt' => ['S' => $fid]
		    ],
		    'Select' => 'ALL_ATTRIBUTES',
		    'ScanIndexForward' => true,
		]);
		foreach ($response['Items'] as $item) {
		    $_SESSION['fname'] = $item['UserName']['S'];
				$status = $item['OnlineStatus']['N'];
				if($status == '0'){
					$sts = 'Offline';
				}
				else{
					$sts = 'Online';
				}
		  }

		?>

		<div class="dropdown">
		  <button onclick="myFunction()" class="dropbtn">Selection Cart</button>
		  <div id="myDropdown" class="dropdown-content">
		  </div>
		</div>

<div id="wrapper">
		<div id="menu">
			<p class="welcome">
				<b><?php echo $_SESSION['fname']; ?></b>
			  (<?php echo $sts; ?>)
			</p>
			<p class="logout">
				<a id="exit" href="#">Exit Chat</a>
			</p>
			<div style="clear: both"></div>
		</div>
		<div id="chatbox">
		<?php
		if($_SESSION['id']<$_SESSION['fid']){
		$file = $_SESSION['id']."_".$_SESSION['fid'];
		}
		else{
		  $file = $_SESSION['fid']."_".$_SESSION['id'];
		}
		$chatlogs = $client->query([
		    'TableName' => 'Chats',
		    'KeyConditionExpression' => 'UsersId = :v_hash and DateAndTime >= :v_range',
		    'ExpressionAttributeValues' =>  [
		        ':v_hash'  => ['S' => $file],
		        ':v_range' => ['S' => '12/02/2017 23:44:36.000000']
		    ]
		]);
		foreach ($chatlogs['Items'] as $item) {
			?>
			<script type = "text/javascript">
			var objDiv = document.getElementById("chatbox");
      objDiv.scrollTop = objDiv.scrollHeight;
		    var time = "<?php echo $item['DateAndTime']['S']; ?>";
				var name = "<?php echo $item['Sender']['S']; ?>";
				var msg = "<?php echo $item['Message']['S']; ?>";
				  var itemno = 1;
				$("#chatbox").append("<div class='msgln'> ("+time+") <b>"+name+"</b> : "+msg+"</div>");
      </script>
			<?php
		}
		$cartitem = $client->query([
		    'TableName' => 'SelectionCart',
		    'KeyConditionExpression' => 'UsersId = :v_hash and DateAndTime >= :v_range',
		    'ExpressionAttributeValues' =>  [
		        ':v_hash'  => ['S' => $file],
		        ':v_range' => ['S' => '12/02/2017 23:44:36.000000']
		    ]
		]);

		foreach ($cartitem['Items'] as $item) {
			?>
			<script type = "text/javascript">
		    var mcode = "<?php echo $item['ProductCode']['S']; ?>";
				var pname = "<?php echo $item['ProductName']['S']; ?>";
		    var mlink = "<?php echo $item['ProductLink']['S']; ?>";
	  		var mimage = "<?php echo $item['ProductThumbnail']['S']; ?>";
        var key;

				var mycart = document.getElementById("myDropdown");
				var aTagc = document.createElement('a');
	      aTagc.setAttribute('href',mlink);
				aTagc.setAttribute('target', '_blank');
	      aTagc.innerHTML = pname;
				var btn = document.createElement('button');
				btn.innerHTML = 'Remove Item';
				var img = document.createElement("img");
	      img.setAttribute("src", mimage);
	      aTagc.appendChild(img);
				var aTagd = document.createElement('div');
				aTagd.setAttribute('id',mcode);
				var span = document.createElement('span');
				span.innerHTML = itemno;
	      aTagd.append(span);
				itemno++;
				btn.onclick = function(){
					 key = $(this).closest("div").attr("id");
					 $(this).closest('div').remove();
					myfunction();
				};
				aTagd.appendChild(aTagc);
				aTagd.appendChild(btn);
				mycart.appendChild(aTagd);
				function myfunction()
					{
						itemno = 1;
						$('#myDropdown span').each(function(){
							this.innerHTML = itemno;
							itemno++;
					});

					$.ajax({
								 type: "POST",
								 url: 'deletecartitem.php',
								 data: { key : key },
								 success: function(data)
								 {
										// alert(data);
								 }
						 });
					}
      </script>
			<?php
		}
		?>
	 </div>

		<form name="message" action="">
			  <input name="usermsg" type="text" id="usermsg" size="63" /> <input
				name="submitmsg" type="submit" id="submitmsg" value="Send" />
		</form>
</div>

	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">

	/* When the user clicks on the button,
	toggle between hiding and showing the dropdown content */
	function myFunction() {
	    document.getElementById("myDropdown").classList.toggle("show");
	}

	// Close the dropdown menu if the user clicks outside of it
	window.onclick = function(event) {
	  if (!event.target.matches('.dropbtn')) {

	    var dropdowns = document.getElementsByClassName("dropdown-content");
	    var i;
	    for (i = 0; i < dropdowns.length; i++) {
	      var openDropdown = dropdowns[i];
	      if (openDropdown.classList.contains('show')) {
	        openDropdown.classList.remove('show');
	      }
	    }
	  }
	}

//jQuery Document
$(document).ready(function(){
	//If user wants to end session

	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to exit this chat?");
		if(exit==true){window.location = 'chatindex.php?logout=true';}
	});
});
//If user submits the form
$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});
		$("#usermsg").attr("value", "");

var msg = {
message: clientmsg,
name: "<?php echo $_SESSION['user']; ?>"
};
		websocket.send(JSON.stringify(msg));
		return false;
});

websocket.onmessage = function(ev) {
	var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
	var msg = JSON.parse(ev.data); //PHP sends Json data
	var type = msg.type; //message type
	var umsg = msg.message; //message text
	var uname = msg.name;
	var mtime = msg.time;
	var mimage = msg.image;
	var mlink = msg.link;
	var mcode = msg.code;
	var pname = msg.productname;
	if(type == 'usermsg')
	{
		$('#chatbox').append("<div class='msgln'>("+mtime+") <b>"+uname+"</b>: "+umsg+"</div>");
		if(mimage!='null'){
      var key;
			var mydiv = document.getElementById("chatbox");
      var aTag = document.createElement('a');
      aTag.setAttribute('href',mlink);
			aTag.setAttribute('target', '_blank');
      aTag.innerHTML = pname;
			var img = document.createElement("img");
      img.setAttribute("src", mimage);
      aTag.appendChild(img);
      mydiv.appendChild(aTag);
			var mycart = document.getElementById("myDropdown");
			var aTagc = document.createElement('a');
      aTagc.setAttribute('href',mlink);
			aTagc.setAttribute('target', '_blank');
      aTagc.innerHTML = pname;
			var btn = document.createElement('button');
			btn.innerHTML = 'Remove Item';
			var img = document.createElement("img");
      img.setAttribute("src", mimage);
      aTagc.appendChild(img);
			var aTagd = document.createElement('div');
			aTagd.setAttribute('id',mcode);
			var span = document.createElement('span');
			span.innerHTML = itemno;
      aTagd.append(span);
			itemno++;
			btn.onclick = function(){
				key = $(this).closest("div").attr("id");
				 $(this).closest('div').remove();
				myfunction();
			};
			aTagd.appendChild(aTagc);
			aTagd.appendChild(btn);
			mycart.appendChild(aTagd);
		}
	}
function myfunction()
	{
		itemno = 1;
		$('#myDropdown span').each(function(){
			this.innerHTML = itemno;
			itemno++;
	});
	$.ajax({
				 type: "POST",
				 url: 'deletecartitem.php',
				 data: { key : key },
				 success : function(data){
        //    alert(data);
				 }
		 });
	}
	if(type == 'system')
	{
		$('#chatbox').append("<div class='msgln'>"+umsg+"</div>");
	}
	var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
	if(newscrollHeight > oldscrollHeight){
		$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
	}
};
function loadlog(){
$("#myDropdown").load("cartrefresh.php");
}
setInterval(loadlog,5000);
</script>
<?php
	}
	?>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">
</script>
</body>
</html>
