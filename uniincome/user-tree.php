<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php'); 
$userid = $_SESSION['userid_matrix'];
$search = $userid;

if (isset($_GET['search-id'])) {
    $search_id = strip_tags($_GET['search-id']);
    if (!empty($search_id)) {
        $sql_check = "SELECT * FROM user WHERE email= ?";
        $stmt = $db->prepare($sql_check);
        $stmt->execute(array($search_id));
        $result_check = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($search_id == $result_check['email']) {
             $search = $search_id;
        }else{
        echo "<script>alert('Access Denied');window.location.assign('tree.php');</script>";
    }
        
    }else{
        echo "<script>alert('Access Denied');window.location.assign('tree.php');</script>";
    } 
}

function tree_data($userid){
    global $db;
    $data= array();
    $sql = "SELECT * FROM tree WHERE userid = ?";
    $stmt = $db->prepare($sql);
   $stmt->execute(array($userid));
   $result = $stmt->fetch(PDO::FETCH_ASSOC);
   $data['left'] = $result['left'];
   $data['middle'] = $result['middle'];
   $data['right'] = $result['right'];
   $data['leftcount'] = $result['leftcount'];
   $data['middlecount'] = $result['middlecount'];
   $data['rightcount'] = $result['rightcount'];
   return $data;


}


?>
<div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tree</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-2">
                        
                    </div>
                    <!-- /.col-lg-2 -->
                    <form>
                        <div class="col-lg-6">
                           <div class="form-group">
                               <input type="text" name="search-id" class="form-control" required autofocus>
                           </div>
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-2">
                           <div class="form-group">
                               <input type="submit" value="Search" class="btn btn-primary">
                           </div>
                        </div>
                        <!-- /.col-lg-6 -->
                    </form>
                    <div class="col-lg-2">
                        
                    </div>
                    <!-- /.col-lg-2 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table" border="0" align="center" style="text-align: center">
                                <tr height="150">
                                    <?php $data = tree_data($search); ?>
                                    <td><?= $data['leftcount']; ?></td>
                                    <td colspan="8"><i class="fa fa-user fa-4x" style="color:orange;"></i><p><?= $search; ?></p><span><?= $data['middlecount']; ?></span></td>
                                    <td><?= $data['rightcount']; ?></td>
                                </tr>
                                <tr height="150">
                                    <?php 
                                    $first_left_user = $data['left'];
                                    $first_middle_user = $data['middle'];
                                    $first_right_user = $data['right'];

                                     ?>
                                    <td colspan="2"><a href="?search-id=<?= $first_left_user; ?>"><i class="fa fa-user fa-4x" style="color:red;"></i><p><?= $first_left_user; ?></p></a></td>
                                    <td colspan="5"><a href="?search-id=<?= $first_middle_user; ?>"><i class="fa fa-user fa-4x" style="color:red;"></i><p><?= $first_middle_user; ?></p></a></td>
                                    <td colspan="2"><a href="?search-id=<?= $first_right_user; ?>"><i class="fa fa-user fa-4x" style="color:red;"></i><p><?= $first_right_user; ?></p></a></td>
                                </tr>
                                <tr height="150">
                                    <?php 
                                    //getting first left user data
                                    $data_left_user = tree_data($first_left_user);
                                    $second_left_user = $data_left_user['left'];
                                    $second_middle_user = $data_left_user['middle'];
                                    $second_right_user = $data_left_user['right'];
                                    //getting data for middle 
                                    $data_middle_user = tree_data($first_middle_user);
                                    $middle_left_user = $data_middle_user['left'];
                                    $middle_middle_user = $data_middle_user['middle'];
                                    $middle_right_user = $data_middle_user['right'];

                                    //getting first right user data
                                    $data_right_user = tree_data($first_right_user);
                                    $third_left_user = $data_right_user['left'];
                                    $third_middle_user = $data_right_user['middle'];
                                    $third_right_user = $data_right_user['right'];

                                     ?>
                                     <!-- left user data -->
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $second_left_user; ?></p></td>
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $second_middle_user; ?></p></td>
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $second_right_user; ?></p></td>
									<!-- middle user data -->
									<td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $middle_left_user; ?></p></td>
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $middle_middle_user; ?></p></td>
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $middle_right_user; ?></p></td>
									<!-- right user data -->
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $third_left_user; ?></p></td>
                                    <td><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $third_middle_user; ?></p></td>
                                    <td colspan="2"><i class="fa fa-user fa-4x" style="color:green;"></i><p><?= $third_right_user; ?></p></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->


<!-- footer include -->
<?php include 'includes/footer.php'; ?>