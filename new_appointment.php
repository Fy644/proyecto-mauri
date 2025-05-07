<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    // Fetch available cars for the dropdown
    $cars = $conn->query("SELECT id, name FROM carros WHERE deleted = 0");

    // Fetch available employees for the dropdown
    $employees = $conn->query("SELECT id, name FROM employees WHERE deleted = 0");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $datetime = date('Y-m-d H:00:00', strtotime($_POST['datetime'])); // Round to the nearest hour
        $client_name = substr($_POST['client_name'], 0, 50); // Limit to 50 characters
        $phone = substr($_POST['phone'], 0, 20); // Limit to 20 digits
        $id_car = $_POST['id_car'];
        $id_employee = $_POST['id_employee'];

        $hour = (int)date('H', strtotime($datetime));
        if (strtotime($datetime) > time() && $hour >= 10 && $hour <= 16) { // Ensure the date is in the future and time is between 10 AM and 4 PM
            $sql = "INSERT INTO citas (datetime, client_name, phone, id_car, id_employee, deleted) 
                    VALUES ('$datetime', '$client_name', '$phone', '$id_car', '$id_employee', 0)";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Appointment created successfully.";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $error_message = "The appointment date must be in the future, and the time must be between 10 AM and 4 PM.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Appointment</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .custom-datetime {
                position: relative;
            }
            .custom-datetime input {
                padding-right: 40px;
            }
            .custom-datetime .calendar-icon {
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
                cursor: pointer;
                color: #6c757d;
                font-size: 1.5rem;
            }
            .custom-datetime .calendar-icon:hover {
                color: #0056b3;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Agencia Elmas Capitos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="inventory.php">Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contactanos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="new_appointment.php">Prueba de coche</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/login.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Create Appointment</h1>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </div>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3 custom-datetime">
                    <label for="datetime" class="form-label">Appointment Date & Time</label>
                    <input type="datetime-local" class="form-control" name="datetime" id="datetime" min="<?php echo date('Y-m-d\TH:00'); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="client_name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" name="client_name" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" pattern="\d{1,20}" title="Phone number must be up to 20 digits" required>
                </div>
                <div class="mb-3">
                    <label for="id_car" class="form-label">Select a Car</label>
                    <select name="id_car" class="form-select" required>
                        <option value="">-- Select a Car --</option>
                        <?php while ($row = $cars->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_employee" class="form-label">Select an Employee</label>
                    <select name="id_employee" class="form-select" required>
                        <option value="">-- Select an Employee --</option>
                        <?php while ($row = $employees->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Appointment</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.querySelector('.calendar-icon').addEventListener('click', function () {
                document.getElementById('datetime').focus();
            });
        </script>
    </body>
</html>
