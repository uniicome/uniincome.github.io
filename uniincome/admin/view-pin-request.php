<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php');
$product_price = 300;
if (isset($_POST['send'])) {
	$userid = $_POST['userid'];
	$amount = $_POST['amount'];
	$id = $_POST['id'];
	$no_of_pin = $amount/$product_price;
	$i = 1;
	while ($i<=$no_of_pin) {
	    $new_pin = pin_gen();
	    $st = $db->prepare("INSERT INTO pin_list(`userid`,`pin`) VALUES(?,?)");
	    $st->execute(array($userid, $new_pin));
	    $i++;
	}
	//update pin request
    $updateQuery = "UPDATE pin_request SET status='close' WHERE id ='$id' LIMIT 1";
    $stmt = $db->prepare($updateQuery);
    
    if ($stmt->execute()) {
       $ot = 'Pin Send Successfully';
    }else{
    	$ot = "<font color='red'>*Contact to Hindi Alerts Admin</font>";
    }
}

function pin_gen(){
	global $db;
	$gen_pin = rand(100000,999999);
	$query = "SELECT * FROM pin_list WHERE pin ='$gen_pin'";
	$stmt = $db->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		pin_gen();
	}else{
		return $gen_pin;
	}
}


?>
<div class="container-fluid">
	<h2>Pin Request Summary</h2>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<table class="table table-bordered ">
				 <tr>
                    <th>S.No</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Send</th>
                </tr>
                    <?php 
                        $query = "SELECT * FROM pin_request WHERE status = 'open' ";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            foreach ($stmt->fetchAll() as $row) {
                                echo "<tr><td>{$row['id']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['amount']}</td>";
                                echo "<td>{$row['date']}</td>";
                                echo "<form method='post'>
                                <input type='hidden' name='userid' value='{$row['email']}'>
                                <input type='hidden' name='amount' value='{$row['amount']}'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <td><input type='submit' value='Send' name='send' class='btn btn-purple'></td></form>";
                            }
                        }else{ ?>
                                <td colspan="6">You havent any pin request</td>
                        <?php } ?>
			</table>
		</div>
	</div>
	<p class="text-muted"><?php echo (isset($ot))? $ot : ''; ?></p>
</div>
<!-- footer include -->
<?php include 'includes/footer.php'; ?>