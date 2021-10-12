<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php'); ?>
<div class="container-fluid">
              <h2 class="page-header">Income History</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td>S.No</td>
                                    <td>User ID</td>
                                    <td>Amount</td>
                                    <td>Date</td>
                                </tr>
                                <?php 
                                    $sql = "SELECT * FROM income_received";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    if ($stmt->rowCount() > 0) {
                                        $count = 1;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            print "<tr>";
                                            print "<td>$count</td>";
                                            print "<td>".$row['userid']."</td>";
                                            print "<td>".$row['amount']."</td>";
                                            print "<td>".$row['date']."</td>";
                                            print "</tr>";
                                            $count++;
                                        }
                                    }else{
                                        echo "<tr>
                                            <td colspan='5'>No Income History </td>
                                            <tr>";
                                    }
                                  ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
<!-- footer include -->
<?php include 'includes/footer.php'; ?>