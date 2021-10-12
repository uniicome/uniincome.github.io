<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php');

if (isset($_GET['userid']) && !empty($_GET['userid'])) {
    $db_userid = strip_tags($_GET['userid']);
    $db_amount = strip_tags($_GET['amount']);
    $date = date("Y-m-d");
    // Here we send the amount to the income_recived table of our datbase and also update the current amount of the same user that we sent payment....
    $sql_query = "INSERT INTO income_received(`userid`, `amount`, `date`) VALUES(?,?,?)";
    $db->prepare($sql_query)->execute(array($db_userid, $db_amount, $date));
    if ($db->prepare("UPDATE income SET current_bal = ? WHERE userid =?")->execute(array(0, $db_userid))) {
    	$output = "Payment Sent Successfully";
    }else{
    	$output = "<font color='red'>Payment not sent Please contact Hindi Alerts Admin</font>";
    }
}
?>

<div class="container-fluid">
	<h2>Your Pin</h2>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
                                    <td>S.No</td>
                                    <td>User ID</td>
                                    <td>Amount</td>
                                    <td>Account</td>
                                    <td>Send</td>
                                </tr>
                                <?php 
                                //here we check our income table amount is equal aur greater than 500
                                    $sql = "SELECT * FROM income WHERE current_bal >= 500";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    if ($stmt->rowCount() > 0) {
                                        $count = 1;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        	// form this query we get the account number of a user that current balance is equal or greater than 500
                                            $query_user = "SELECT account FROM user WHERE email = ";
                                            $stmt_user = $db->prepare($query_user);
                                            $stmt_user->execute(array($row['userid']));
                                            $rlt = $stmt_user->fetch(PDO::FETCH_ASSOC);
                                            $account = $rlt['account'];
                                            print "<tr>";
                                            print "<td>$count</td>";
                                            print "<td>".$row['userid']."</td>";
                                            print "<td>".$row['current_bal']."</td>";
                                            print "<td>".$account."</td>";?>
                                             <td>
                                                 <a class="btn btn-success" href="income.php?userid=<?= $row['userid']; ?>&amount=<?= $row['current_bal']; ?>">Send</a>
                                             </td>
                                            <?php
                                            print "</tr>";
                                            $count++;
                                        }
                                    }else{
                                        echo "<tr>
                                            <td colspan='5'>No Income</td>
                                            <tr>";
                                    }
                                 ?>
				</table>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
	<p class="text-muted"><?php echo (isset($output)? $output : ''); ?></p>
</div>
<!-- footer include -->
<?php include 'includes/footer.php'; ?>