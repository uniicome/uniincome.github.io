<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php');

	if (isset($_GET['pin_request'])) {
		//set the varibale for insert data into datbase....
		$email = $_SESSION['userid_matrix'];
		$amount = trim($_GET['amount']);
		$date = date('y-m-d');
		//checking id amount empty or not
		if ($amount != '') {
			//query for insert data into pin_request database table......
			$request_pin_query = "INSERT INTO `pin_request`(`email`,`amount`,`date`) VALUES(?,?,?)";
			$stmt = $db->prepare($request_pin_query);
			$result = $stmt->execute(array($email, $amount, $date));
			if ($result !== false) {
				$output = "Your Pin Request Successfully Sent";
			}else{
				$output = "<font color='red'>Unknow Error Occurred Please Contact Admin</font>";
			}
		}else{
			$output = "<font color='red'>Please Enter the Amount</font>";
		}		
	}
 ?>

<div class="container-fluid">
	<h2>Enter Your Amount</h2>
	<div class="row"><div class="col-md-5"></div>
		<div class="col-md-2">
			<form method="get">
				<div class="form-group">
					<label for="amount">Amount</label>
					<input type="text" class="form-control" name="amount" id="amount">
				</div>
				<button type="submit" class="btn btn-purple" name='pin_request'>Submit</button>
			</form>
		</div>
	</div>
	<p class="text-muted"><?php if (isset($output)) {echo $output;} ?></p>
</div>

<div class="container-fluid">
	<h2>Your Pin Request Summary</h2>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<table class="table table-bordered ">
				<tr>
					<th>Sr. No</th>
					<th>Amount</th>
					<th>Status</th>
				</tr>
				<?php 
				//getting pin request data from database table named pin_request
					$get_data_query = "SELECT * FROM pin_request WHERE email = ?";
					$stmt = $db->prepare($get_data_query);
					$stmt->execute(array($_SESSION['userid_matrix']));
					if ($stmt->rowCount() > 0) {
						foreach ($stmt->fetchAll() as $row) {
							echo "<tr><td>{$row["id"]}</td>";
							echo "<td>{$row["amount"]}</td>";
							echo "<td>{$row["status"]}</td></tr>";
						}
					}else{
						echo '<tr><td colspan="3">You haven\'t requested any pin</td></tr>';
					}
				 ?>
			</table>
		</div>
	</div>
</div>
<!-- footer include -->
<?php include 'includes/footer.php'; ?>