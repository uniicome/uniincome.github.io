<!-- header file -->
<?php include('includes/header.php'); ?>
<!-- menu Here -->
<?php include('includes/menu.php'); 

$userid = $_SESSION['userid_matrix'];
$capping = 500;

//user information after submission the form data.....

if (isset($_GET['join_user'])) {
    $pin = $_GET['pin'];
    $email = $_GET['email'];
    $mobile = $_GET['mobile'];
    $address = $_GET['address'];
    $account = $_GET['account'];
    $under_userid = $_GET['under_userid'];
    $side = $_GET['side'];
    $flag = 0;
    $password = '123456';

if (!empty($pin) && !empty($email) && !empty($mobile) && !empty($address) && !empty($account) && !empty($under_userid) && !empty($side)) {

    if (pin_check($pin)) {

        if (email_check($email)) {
            if (!email_check($under_userid)) {
                if (side_check($under_userid, $side)) {
                    $flag = 1;
                }else{
                    //checking side
                echo "<script>
                        alert('Selected Side is not empty');
                     </script>";                
                }
        }else{
            //Checking Under_userid is available
            echo "<script>
                      alert('Your Under User Not Available');
                </script>";
        }
        }else{
            //checking Email....
        echo "<script>
                 alert('Your Email Already Registerd');
            </script>";
        }
    }else{
        //checking pin....
        echo "<script>
                 alert('Please Enter a Correct Pin');
             </script>";
    }
}else{
    //checking all field are not empty....
    echo "<script>
            alert('Please Fill Up All Details');
        </script>";
}

if ($flag == 1) {
    //Created new user......
    $query = "INSERT INTO user(email, password, mobile, address, account, under_userid, side) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $st = $db->prepare($query);
    if ($st->execute(array($email, $password, $mobile, $address, $account, $under_userid, $side))) {
        print "<script>alert('You are Registerd Please login!');</script>";
    }
    //insert into tree a new user for futher add new user by side.....
    $query = "INSERT INTO tree(userid) VALUES(?)";
    $db->prepare($query)->execute(array($email));


    // update new user in tree......
    $query = "UPDATE tree SET `$side` = ? WHERE userid = ? ";
    $db->prepare($query)->execute(array($email, $under_userid));

    //Create Income Table with new User
    $query = "INSERT INTO income(userid) VALUES(?)";
    $db->prepare($query)->execute(array($email));

    //removing pin after everything setup.....

    $query = "UPDATE pin_list SET status = 'close' WHERE pin= $pin";
    $db->exec($query);

    //update Income and update counting 

    $temp_under_userid = $under_userid;
    $temp_side = $side;
    $temp_side_count = $side."count";// leftcount, middlecount, rightcount

    $total_count = 1;

    while ($total_count > 0) {
        $query123 = "SELECT * FROM tree WHERE userid = ?";
        $stmt123 =  $db->prepare($query123);
        $stmt123->execute(array($temp_under_userid));
        $result = $stmt123->fetch(PDO::FETCH_ASSOC);
        $current_temp_side_count = $result[$temp_side_count]+1;

        $query124 = "UPDATE tree SET `$temp_side_count`= ? WHERE userid = ?";
        $db->prepare($query124)->execute(array($current_temp_side_count,$temp_under_userid));

        //Updating Income
        if ($temp_under_userid !="") {
            $income_data = income($temp_under_userid);
            if ($income_data['day_bal'] < $capping) {
                 $tree_data = tree($temp_under_userid);
                 $temp_left_count = $tree_data['leftcount'];
                 $temp_middle_count = $tree_data['middlecount'];
                 $temp_right_count = $tree_data['rightcount'];
                 if ($temp_left_count > 0 && $temp_right_count > 0 && $temp_middle_count > 0 ) {
                     if ($temp_side == 'left') {
                        if ($temp_left_count <= $temp_right_count && $temp_left_count <= $temp_middle_count) {
                             $new_data_bal = $income_data['day_bal']+100;
                             $new_current_bal = $income_data['current_bal']+100;
                             $new_total_bal = $income_data['total_bal']+100;

                             //upadting income into database
                             $sql = "UPDATE income SET daily_bal = ? , current_bal = ?, total_bal = ? WHERE userid = ? LIMIT 1";
                             $db->prepare($sql)->execute(array($new_data_bal, $new_current_bal, $new_total_bal, $temp_under_userid ));
                        }
                         
                     }
                     else if($temp_side == 'middle'){
                      if ($temp_middle_count <= $temp_left_count && $temp_middle_count <= $temp_right_count) {
                             $new_data_bal = $income_data['day_bal']+100;
                             $new_current_bal = $income_data['current_bal']+100;
                             $new_total_bal = $income_data['total_bal']+100;

                             //upadting income into database
                             $sql = "UPDATE income SET daily_bal = ? , current_bal = ?, total_bal = ? WHERE userid = ? LIMIT 1";
                             $db->prepare($sql)->execute(array($new_data_bal, $new_current_bal, $new_total_bal, $temp_under_userid ));
                        }

                     }else{
                        if ($temp_left_count >= $temp_right_count && $temp_left_count >= $temp_middle_count) {
                             $new_data_bal = $income_data['day_bal']+100;
                             $new_current_bal = $income_data['current_bal']+100;
                             $new_total_bal = $income_data['total_bal']+100;

                             //upadting income into database
                             $sql = "UPDATE income SET daily_bal = ? , current_bal = ?, total_bal = ? WHERE userid = ? LIMIT 1";
                             $db->prepare($sql)->execute(array($new_data_bal, $new_current_bal, $new_total_bal, $temp_under_userid ));
                        }

                     }
                 }
             } 
             //change under_userid....
             $next_under_userid = getUnderUserID($temp_under_userid);
             $temp_side = getUnderIDSide($temp_under_userid);
             $temp_side_count = $temp_side."count";
             $temp_under_userid = $next_under_userid;
        }
            if ($temp_under_userid == "") {
                $total_count = 0;
            }
    }

}

}








function getUnderUserID($userid){
    global $db;
    $sql = "SELECT * FROM user WHERE email = ?";
    $st=$db->prepare($sql);
    $st->execute(array($userid));
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result['under_userid'];
}

function getUnderIDSide($userid){
    global $db;
    $sql = "SELECT * FROM user WHERE email = ?";
    $st=$db->prepare($sql);
    $st->execute(array($userid));
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result['side'];
}

function income($userid){
    global $db;
    $data = array();
    $query = "SELECT * FROM income WHERE userid = ?";
    $stmt = $db->prepare($query);
    $stmt->execute(array($userid));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $data['day_bal'] = $result['daily_bal'];
    $data['current_bal'] = $result['current_bal'];
    $data['total_bal'] = $result['total_bal'];
    return $data;
}

function tree($userid){
    global $db;
    $data = array();
    $query = "SELECT * FROM tree WHERE userid = ?";
    $st = $db->prepare($query);
    $st->execute(array($userid));
    $result = $st->fetch(PDO::FETCH_ASSOC);
    $data['left'] = $result['left'];
    $data['middle'] = $result['middle'];
    $data['right'] = $result['right'];
    $data['leftcount'] = $result['leftcount'];
    $data['middlecount'] = $result['middlecount'];
    $data['rightcount'] = $result['rightcount'];
    return $data;
}

function pin_check($pin){
    global $db, $userid;
    $query = "SELECT * FROM pin_list WHERE pin = ? AND userid = ? AND status= 'open'";
    $st = $db->prepare($query);
    $st->execute(array($pin, $userid));
    if ($st->rowCount() > 0) {
        return true;
    }else{
        return false;
    }
}

function email_check($email){
    global $db;
    $query = "SELECT * FROM user WHERE email = ?";
    $st = $db->prepare($query);
    $st->execute(array($email));
    if ($st->rowCount()> 0) {
        return false;
    }else{
        return true;
    }
}

function side_check($email, $side){
    global $db;
    $query = "SELECT * FROM tree WHERE userid = ?";
    $st = $db->prepare($query);
    $st->execute(array($email));
    $result = $st->fetch(PDO::FETCH_ASSOC);
    $side_value = $result[$side];
    if ($side_value == "") {
        return true;
    }else{
        return false;
    }
}

?>

<div class="container-fluid">                 
               <h2>Join User</h1>
                <div class="row">
                	<div class="col-lg-3"></div>
                    <div class="col-lg-6">
                           <form method="get">
                               <div class="form-group">
                                <label>Pin</label>
                                   <input type="text" name="pin" required class="form-control" autofocus>
                               </div>

                               <div class="form-group">
                                <label>E-mail</label>
                                   <input type="text" name="email" required class="form-control">
                               </div>

                               <div class="form-group">
                                <label>Mobile</label>
                                   <input type="text" name="mobile" required class="form-control">
                               </div>

                               <div class="form-group">
                                <label>Address</label>
                                   <input type="text" name="address" required class="form-control">
                               </div>

                               <div class="form-group">
                                <label>Account</label>
                                   <input type="text" name="account" required class="form-control">
                               </div>

                               <div class="form-group">
                                <label>Under User ID</label>
                                   <input type="text" name="under_userid" required class="form-control">
                               </div>

                               <div class="form-group">
                                <label>Side</label><br>
                                   <input type="radio" name="side" value="left" required>Left
                                   <input type="radio" name="side" value="right" required>Right
                                   <input type="radio" name="side" value="middle" required>Middle
                               </div>

                               <div class="form-group">
                                   <input type="submit" name="join_user" value="Join New User"  class="btn btn-purple ">
                               </div>
                           </form>
                       </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->





<!-- footer include -->
<?php include 'includes/footer.php'; ?>