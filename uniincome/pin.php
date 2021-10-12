<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php'); ?>

<div class="container-fluid">
	<h2>Your Pin</h2>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Sr. No</th>
						<th>Pin</th>
						<th>Status</th>
					</tr>
					<?php 
						$data_query = "SELECT * FROM pin_list WHERE userid = ?";
						$stmt = $db->prepare($data_query);
						$stmt->execute(array($_SESSION['userid_matrix']));
						if ($stmt->rowCount() > 0) {
							foreach ($stmt->fetchAll() as $row) {
								echo "<tr><td>{$row['id']}</td>";
								echo "<td>{$row['pin']}</td>";
								echo "<td>{$row['status']}</td></tr>";
							}
						}else{
							echo '<tr><td colspan="3">You haven\'t Approved Pin Yet</td></tr>';
						}



					 ?>
				</table>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>





<!-- footer include -->
<?php include 'includes/footer.php'; ?>