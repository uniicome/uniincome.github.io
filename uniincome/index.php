<?php 
session_start();
if (isset($_SESSION['id']) && $_SESSION['login_type']=='user_matrix') {
    header("Location:home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Matrix MLM Login User</title>
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- own css stylesheet -->
    <link href="assets/own.css" rel="stylesheet" />
    <!-- font awesome    -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="row">
        <div class=" justify-content-center col-md-offset-5 col-md-4">
            <div class="form-login">
            <h4 style="color: white">Matrix User Login</h4>
            <form action="login.php" method="post">
            <input type="text" id="userName" name="email" class="form-control input-sm chat-input" placeholder="username" />
            </br>
            <input type="text" id="userPassword" name="password" class="form-control input-sm chat-input" placeholder="password" />
            </br>
            <div class="wrapper-form">
            <span class="group-btn">     
                <button class="btn btn-warning btn-md" type="submit">login <i class="fa fa-sign-in"></i></button>
            </span>
            </form>
            </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


