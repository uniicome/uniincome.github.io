<?php 
session_start();
require 'includes/db.php';
extract($_POST);
$query = "SELECT * FROM admin WHERE userid= ? AND password = ?";
$stmt = $db->prepare($query);
$stmt->execute(array($email, $password));
$rows = $stmt->fetchAll();
if ($stmt->rowCount()>0) {
	$_SESSION['admin_matrix'] = $email;
	$_SESSION['id'] = session_id();
	$_SESSION['login_type'] = "admin_matrix";
	
	echo "<p class='alert alert-success'>
		<script>document.write('You Are Logged In');window.location.assign('home.php');</script>
		</p>";
}else{
	echo "<p class='alert alert-Warning'>
		<script>document.write('Your ID and Password is Wrong');window.location.assign('index.php');</script>
		</p>";
}


 ?>