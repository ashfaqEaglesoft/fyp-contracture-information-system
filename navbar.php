<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="contractor_dashboard.php">Contractor information</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php
            if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
                echo '<li class="nav-item"><a class="nav-link" href="contractor_dashboard.php">Home</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="add_worker.php">Workers</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="add_expenses.php">Expenses</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="./admin/admin_login.php">Admin Login</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
