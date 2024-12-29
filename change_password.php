<?php
include("session.php"); // Assuming session.php handles session and user authentication

// Handling password change
if (isset($_POST['updatepassword'])) {
    $curr_password = mysqli_real_escape_string($con, $_POST['curr_password']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_new_password = mysqli_real_escape_string($con, $_POST['confirm_new_password']);

    // Fetch the current password from the database for the logged-in user
    $query = "SELECT password FROM users WHERE id = '$userid'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];

    // Verify the current password
    if (password_verify($curr_password, $hashed_password)) {
        // Check if new password and confirm password match
        if ($new_password == $confirm_new_password) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_query = "UPDATE users SET password = '$new_hashed_password' WHERE id = '$userid'";
            if (mysqli_query($con, $update_query)) {
                echo "<div class='alert alert-success'>Password updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error updating password: " . mysqli_error($con) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Current password is incorrect.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Expense Manager - Change Password</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Feather JS for Icons -->
    <script src="js/feather.min.js"></script>

</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="uploads/<?php echo $profile_path; ?>" width="120">
                <h5><?php echo $username; ?></h5>
                <p><?php echo $useremail; ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action "><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action "><span data-feather="dollar-sign"></span> Manage Expenses</a>
                <a href="expensereport.php" class="list-group-item list-group-item-action"><span data-feather="file-text"></span> Expense Report</a>
            </div>
            <div class="sidebar-heading">Settings </div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action"><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light  border-bottom">
                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img img-fluid rounded-circle" src="uploads/<?php echo $profile_path; ?>" width="25">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Your Profile</a>
                                <a class="dropdown-item" href="#">Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h5>Hi <?php echo $firstname ?>! You can change your password here</h5>
                <div class="row mt-3">
                    <div class="col-md">
                        <form class="form" action="" method="post" id="passwordChangeForm" autocomplete="off">
                            <div class="form-group">
                                <div class="col">
                                    <label for="curr_password">Enter Current Password</label>
                                    <input type="password" class="form-control" name="curr_password" id="curr_password" placeholder="Current Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <label for="new_password">Enter New Password</label>
                                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <label for="confirm_new_password">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm New Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-block btn-primary" name="updatepassword" type="submit">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>

    <script>
        feather.replace()
    </script>

</body>

</html>
