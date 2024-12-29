<?php
require('config.php');

// Error handling
if (isset($_POST['firstname'])) {
    // Check if passwords match
    if ($_POST['password'] == $_POST['confirm_password']) {
        // Sanitize user input
        $firstname = stripslashes($_POST['firstname']);
        $firstname = mysqli_real_escape_string($con, $firstname);

        $lastname = stripslashes($_POST['lastname']);
        $lastname = mysqli_real_escape_string($con, $lastname);

        $email = stripslashes($_POST['email']);
        $email = mysqli_real_escape_string($con, $email);

        $password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($con, $password);

        // Hash the password using password_hash for better security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert user data
        $query = "INSERT INTO `users` (firstname, lastname, password, email) VALUES (?, ?, ?, ?)";
        if ($stmt = $con->prepare($query)) {
            // Bind the parameters to the SQL query
            $stmt->bind_param("ssss", $firstname, $lastname, $hashed_password, $email);

            // Execute the query
            if ($stmt->execute()) {
                header("Location: login.php"); // Redirect to login page after successful registration
                exit();
            } else {
                echo "ERROR: Unable to register the user. Please try again later.";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            die("Error preparing the statement: " . $con->error);
        }
    } else {
        // If passwords don't match, display an error message
        echo "ERROR: Passwords do not match. Please check your password and confirmation password.";
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
    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            color: #000;
            background: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .form-control {
            height: 40px;
            box-shadow: none;
            color: #495057;
        }

        .form-control:focus {
            border-color: #5cb85c;
        }

        .form-control,
        .btn {
            border-radius: 3px;
        }

        .signup-form {
            width: 450px;
            margin: 0 auto;
            padding: 30px 0;
            font-size: 15px;
        }

        .signup-form h2 {
            color: #636363;
            margin: 0 0 15px;
            position: relative;
            text-align: center;
        }

        .signup-form h2:before,
        .signup-form h2:after {
            content: "";
            height: 2px;
            width: 30%;
            background: #d4d4d4;
            position: absolute;
            top: 50%;
            z-index: 2;
        }

        .signup-form h2:before {
            left: 0;
        }

        .signup-form h2:after {
            right: 0;
        }

        .signup-form .hint-text {
            color: #999;
            margin-bottom: 30px;
            text-align: center;
        }

        .signup-form form {
            color: #999;
            border-radius: 3px;
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid #ddd;
        }

        .signup-form .form-group {
            margin-bottom: 20px;
        }

        .signup-form input[type="checkbox"] {
            margin-top: 3px;
        }

        .signup-form .btn {
            font-size: 16px;
            font-weight: bold;
            min-width: 140px;
            outline: none !important;
        }

        .signup-form .row div:first-child {
            padding-right: 10px;
        }

        .signup-form .row div:last-child {
            padding-left: 10px;
        }

        .signup-form a:hover {
            text-decoration: none;
        }

        .signup-form form a {
            color: #5cb85c;
            text-decoration: none;
        }

        .signup-form form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="signup-form">
        <form action="" method="POST" autocomplete="off">
            <h2>Register</h2>
            <div class="form-group">
                <div class="row">
                    <div class="col"><input type="text" class="form-control" name="firstname" placeholder="First Name" required="required"></div>
                    <div class="col"><input type="text" class="form-control" name="lastname" placeholder="Last Name" required="required"></div>
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required="required">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="required">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger btn-lg btn-block" style="border-radius:0%;">Register</button>
            </div>
        </form>
        <div class="text-center">Already have an account? <a class="text-success" href="login.php">Login Here</a></div>
    </div>
</body>

<!-- Bootstrap core JavaScript -->
<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
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

</html>
