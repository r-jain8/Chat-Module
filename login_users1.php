<?php
echo 'Either the User Name or the password you entered is incorrect. Please enter again!';

session_start();
session_destroy();
?>
<html lang="en">
<head>
  <title>User Login</title>
  <meta name="author" content="Raunak Jain">

  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Login form</h2>
  <form action="" method="POST" class="form-inline">
    <div class="form-group">
      <label for="UserName">User Name:</label>
      <input type="text" name="UserName" class="form-control" id="UserName" placeholder="Enter User Name">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
    </div>
    <input type="submit" name="Submit" value="Submit" class="btn btn-default"/>
  </form>
</div>
<?php

if(isset($_POST['UserName'])){
   $UserName = $_POST['UserName'];
}
if(isset($_POST['password'])){
   $password=$_POST['password'];
}
if(isset($_POST['Submit'])){
  if(!empty($UserName) && !empty($password)){
    //echo 'Welcome Back! ' .$UserName ;

          session_start();
          $_SESSION["username"]=$UserName;
          $_SESSION["password"]=$password;
          ?>
          <script language="javascript">
          //alert("Welcome Back!");
          window.location = 'check_users.php';
          </script>
          <?php
        }
  }

?>
</body>
</html>
