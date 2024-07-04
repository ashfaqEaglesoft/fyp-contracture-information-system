<?php
session_start();
include('config.php');

// Check if contractor is logged in, redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Check if site ID is provided in the URL
if (!isset($_GET['site_id'])) {
    // Redirect to error page if site ID is not provided
    header("Location: error.php");
    exit;
}

// Get site ID from URL
$site_id = $_GET['site_id'];

// Initialize variables to store form data
$date = $floor_tile = $bath_tile = $front_elevation_tile = $cement_type = $gate = $water_pump_tank = $base_beams = $roof_slab_type = $paint = $steel = $wood_window_door = $bricks_sand_concrete_stone = $electricity_wire_switches = "";
$date_err = $floor_tile_err = $bath_tile_err = $front_elevation_tile_err = $cement_type_err = $gate_err = $water_pump_tank_err = $base_beams_err = $roof_slab_type_err = $paint_err = $steel_err = $wood_window_door_err = $bricks_sand_concrete_stone_err = $electricity_wire_switches_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate date
    if (empty(trim($_POST["date"]))) {
        $date_err = "Please enter date.";
    } else {
        $date = trim($_POST["date"]);
    }

    // Validate floor tile
    // Example validation, you can add similar validations for other fields
    if (empty(trim($_POST["floor_tile"]))) {
        $floor_tile_err = "Please enter floor tile details.";
    } else {
        $floor_tile = trim($_POST["floor_tile"]);
    }

    // Check input errors before inserting into database
    if (empty($date_err) && empty($floor_tile_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO material_details (site_id, date, floor_tile, bath_tile, front_elevation_tile, cement_type, gate, water_pump_tank, base_beams, roof_slab_type, paint, steel, wood_window_door, bricks_sand_concrete_stone, electricity_wire_switches) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("issssssssssssss", $param_site_id, $param_date, $param_floor_tile, $param_bath_tile, $param_front_elevation_tile, $param_cement_type, $param_gate, $param_water_pump_tank, $param_base_beams, $param_roof_slab_type, $param_paint, $param_steel, $param_wood_window_door, $param_bricks_sand_concrete_stone, $param_electricity_wire_switches);
            
            // Set parameters
            $param_site_id = $site_id;
            $param_date = $date;
            $param_floor_tile = $floor_tile;
            $param_bath_tile = $_POST["bath_tile"]; // Example for other fields
            $param_front_elevation_tile = $_POST["front_elevation_tile"];
            $param_cement_type = $_POST["cement_type"];
            $param_gate = $_POST["gate"];
            $param_water_pump_tank = $_POST["water_pump_tank"];
            $param_base_beams = $_POST["base_beams"];
            $param_roof_slab_type = $_POST["roof_slab_type"];
            $param_paint = $_POST["paint"];
            $param_steel = $_POST["steel"];
            $param_wood_window_door = $_POST["wood_window_door"];
            $param_bricks_sand_concrete_stone = $_POST["bricks_sand_concrete_stone"];
            $param_electricity_wire_switches = $_POST["electricity_wire_switches"];
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to contractor dashboard after successful addition
                header("Location: contractor_dashboard.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Site Material Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Site Material Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?site_id=<?php echo $site_id; ?>" method="post">
    <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Floor Tile:</label>
                    <input type="text" name="floor_tile" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Bath Tile:</label>
                    <input type="text" name="bath_tile" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Front Elevation Tile:</label>
                    <input type="text" name="front_elevation_tile" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Cement Type:</label>
                    <input type="text" name="cement_type" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Gate:</label>
                    <input type="text" name="gate" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Water pump and Water Tank with Plumber Pipe and Tap:</label>
                    <input type="text" name="water_pump_tank" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Base, Beams:</label>
                    <input type="text" name="base_beams" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Roof Slab Type (RCC):</label>
                    <input type="text" name="roof_slab_type" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Paint:</label>
                    <input type="text" name="paint" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Steel:</label>
                    <input type="text" name="steel" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Wood, Window, Door:</label>
                    <input type="text" name="wood_window_door" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Bricks, Sand, Concrete, Stone:</label>
                    <input type="text" name="bricks_sand_concrete_stone" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Electricity Wire and Switches:</label>
                    <input type="text" name="electricity_wire_switches" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
            <span class="invalid-feedback"><?php echo $date_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </form>
</div>
</body>
</html>
