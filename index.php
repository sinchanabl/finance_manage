<?php
include("session.php"); // Include session start and user check

// Date range calculations for 1 month
$one_month_ago = date("Y-m-d", strtotime("-1 month"));
$exp_category_dc = mysqli_query($con, "SELECT c.category_name 
                                        FROM transactions t 
                                        JOIN table_categories c ON t.category_id = c.category_id 
                                        WHERE t.user_id = '$userid' 
                                        AND t.tdate >= '$one_month_ago' 
                                        GROUP BY c.category_name");

$exp_amt_dc = mysqli_query($con, "SELECT SUM(t.amount) 
                                  FROM transactions t 
                                  JOIN table_categories c ON t.category_id = c.category_id 
                                  WHERE t.user_id = '$userid' 
                                  AND t.tdate >= '$one_month_ago' 
                                  GROUP BY c.category_name");

// Last week date range
$one_week_ago = date("Y-m-d", strtotime("-1 week"));
$exp_date_line = mysqli_query($con, "SELECT DATE_FORMAT(t.tdate, '%b %d') AS day_month 
                                     FROM transactions t 
                                     WHERE t.user_id = '$userid' 
                                     AND t.tdate >= '$one_week_ago' 
                                     GROUP BY t.tdate");

$exp_amt_line = mysqli_query($con, "SELECT SUM(t.amount) 
                                    FROM transactions t 
                                    WHERE t.user_id = '$userid' 
                                    AND t.tdate >= '$one_week_ago' 
                                    GROUP BY t.tdate");

// Yearly Expenses Query
$yearly_expenses_query = "SELECT YEAR(t.tdate) AS year, SUM(t.amount) AS total_expense
                          FROM transactions t
                          WHERE t.user_id = '$userid'
                          GROUP BY YEAR(t.tdate)
                          ORDER BY YEAR(t.tdate)";
$yearly_expenses_result = mysqli_query($con, $yearly_expenses_query);
$year_labels = [];
$yearly_expense_data = [];
while ($row = mysqli_fetch_assoc($yearly_expenses_result)) {
    $year_labels[] = $row['year'];
    $yearly_expense_data[] = $row['total_expense'];
}

// Monthly Expenses Query
$monthly_expenses_query = "SELECT DATE_FORMAT(t.tdate, '%Y-%m') AS month_year, SUM(t.amount) AS total_expense
                           FROM transactions t
                           WHERE t.user_id = '$userid'
                           AND t.tdate >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
                           GROUP BY DATE_FORMAT(t.tdate, '%Y-%m')
                           ORDER BY t.tdate";
$monthly_expenses_result = mysqli_query($con, $monthly_expenses_query);
$monthly_labels = [];
$monthly_expense_data = [];
while ($row = mysqli_fetch_assoc($monthly_expenses_result)) {
    $monthly_labels[] = $row['month_year'];
    $monthly_expense_data[] = $row['total_expense'];
}

// Expense for various time periods
$today_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                     FROM transactions t 
                                     WHERE t.user_id = '$userid' 
                                     AND t.tdate = CURDATE()");

$yesterday_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                         FROM transactions t 
                                         WHERE t.user_id = '$userid' 
                                         AND t.tdate = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

$this_week_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                         FROM transactions t 
                                         WHERE t.user_id = '$userid' 
                                         AND t.tdate >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");

$this_month_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                          FROM transactions t 
                                          WHERE t.user_id = '$userid' 
                                          AND t.tdate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

$this_year_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                         FROM transactions t 
                                         WHERE t.user_id = '$userid' 
                                         AND t.tdate >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");

$total_expense = mysqli_query($con, "SELECT SUM(t.amount) 
                                     FROM transactions t 
                                     WHERE t.user_id = '$userid'");

// Fetching the expense amounts
$today_expense_amount = '0' + mysqli_fetch_assoc($today_expense)['SUM(t.amount)'];
$yesterday_expense_amount = '0' + mysqli_fetch_assoc($yesterday_expense)['SUM(t.amount)'];
$this_week_expense_amount = '0' + mysqli_fetch_assoc($this_week_expense)['SUM(t.amount)'];
$this_month_expense_amount = '0' + mysqli_fetch_assoc($this_month_expense)['SUM(t.amount)'];
$this_year_expense_amount = '0' + mysqli_fetch_assoc($this_year_expense)['SUM(t.amount)'];
$total_expense_amount = '0' + mysqli_fetch_assoc($total_expense)['SUM(t.amount)'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Expense Manager - Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/feather.min.js"></script>
    <style>
        .card a { color: #000; font-weight: 500; }
        .card a:hover { color: #28a745; text-decoration: dotted; }
        .try { font-size: 28px; color: #333; padding: 5px 0px 0px 0px; }
        .container { padding: 0px 20px 20px 20px; }
        .card.text-center { border: 3px solid #ccc; padding: 10px; margin: 10px; background-color: #f8f9fa; border-radius: 5px; }
        .card-title { font-size: 17.5px; margin-bottom: 1px; color: #333; }
        .card-text { font-size: 24px; font-weight: bold; color: #6c757d; }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="uploads\default_profile.png" width="120">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action "><span data-feather="dollar-sign"></span> Manage Expenses</a>
                <a href="expensereport.php" class="list-group-item list-group-item-action"><span data-feather="file-text"></span> Expense Report</a>
            </div>
            <div class="sidebar-heading">Settings</div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light  border-bottom">
                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>
                <div class="col-md-0 text-center">
                    <h3 class="try">Dashboard</h3>
                </div>
            </nav>
            <div class="container-fluid">
                <h4 class="mt-4">Full-Expense Report</h4>
                <div class="row">
                    <div class="container mt-4">
                        <div class="row">
                            <!-- Expense Cards -->
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Today's Expense</h5>
                                        <p class="card-text">₹<?php echo $today_expense_amount; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Yesterday's Expense</h5>
                                        <p class="card-text">₹<?php echo $yesterday_expense_amount; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">This Week's Expense</h5>
                                        <p class="card-text">₹<?php echo $this_week_expense_amount; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">This Month's Expense</h5>
                                        <p class="card-text">₹<?php echo $this_month_expense_amount; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- You can add Yearly and Monthly Charts Here using Chart.js -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
