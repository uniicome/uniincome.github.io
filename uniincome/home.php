<!-- header file here-->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php'); ?>
<!-- my earning thershold limit -->
<div class="container-fluid">
  <h2>Your Earning</h2>
  <?php 
    		$income_query = "SELECT current_bal FROM income WHERE userid= ?";
    		$stmt = $db->prepare($income_query);
    		$stmt->execute(array($_SESSION['userid_matrix']));
    		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
    		if ($rows['current_bal'] <1000) {
    			$bar = $rows['current_bal']/10;
    		}else{
    			$bar = 100;
    		}
    	 ?>
  <div class="progress">
    <div class="progress-bar-color progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:<?= $bar; ?>%"><?= $bar; ?>%
    </div>
  </div>
  <p class="text-muted">Your Total Earning is <?= $rows['current_bal']; ?> out of 1000</p>
</div> 

<!-- More Menu -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-3">
			<div class="panel panel-default">
  				<div class="panel-body">Pin Request</div>
  				<div class="panel-footer"><a class="btn btn-purple" href="pin-request.php">Go To Pin Request Page</a></div>
			</div>
		</div>
		<!-- end of col md 6 -->
		<div class="col-md-2"></div>
		<div class="col-md-3">
			<div class="panel panel-default">
  				<div class="panel-body">Pin</div>
  				<div class="panel-footer"><a class="btn btn-purple" href="pin.php">Go To Pin Page</a></div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
		<!-- end of col md 6 -->
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-3">
			<div class="panel panel-default">
  				<div class="panel-body">Join User</div>
  				<div class="panel-footer"><a class="btn btn-purple" href="join-user.php">Go To Join User Page</a></div>
			</div>
		</div>
		<!-- end of col md 6 -->
		<div class="col-md-2"></div>
		<div class="col-md-3">
			<div class="panel panel-default">
  				<div class="panel-body">User Tree</div>
  				<div class="panel-footer"><a class='btn btn-purple' href="user-tree.php">Go To Check User Tree</a></div>
			</div>
		</div>
		<div class="col-md-2"></div>
		<!-- end of col md 6 -->
	</div>
</div>








<!-- footer here -->
<?php include('includes/footer.php') ?>