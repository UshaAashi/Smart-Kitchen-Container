<?php
include 'database.php';

// Check if the POST data is not empty
if (!empty($_POST)) {
    // Check if all required fields are present in the POST data
    if (isset($_POST['ID'], $_POST['Rice_Stock'], $_POST['Pulse_Stock'])) {
        // Assign POST data to variables
        $ID = $_POST['ID'];
        $Rice_Stock = $_POST['Rice_Stock'];
        $Pulse_Stock = $_POST['Pulse_Stock'];

        // Get the current time and date
        date_default_timezone_set("Asia/Calcutta");
        $tm = date("H:i:s");
        $dt = date("Y-m-d");

        try {
            // Update data in the smart_kitchen_container table
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE smart_kitchen_container SET Rice_Stock = ?, Pulse_Stock = ?, Time = ?, Date = ? WHERE ID = ?";
            $q = $pdo->prepare($sql);
            $q->execute([$Rice_Stock, $Pulse_Stock, $tm, $dt, $ID]);

            // Insert data into the smart_kitchen_container_record table
            $sql = "INSERT INTO smart_kitchen_container_record (ID, Board, Rice_Stock, Pulse_Stock, Time, Date) VALUES (?, ?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute([$ID, $ID, $Rice_Stock, $Pulse_Stock, $tm, $dt]);

            // Disconnect from the database
            Database::disconnect();
            
            // Send success response
            echo json_encode(array(
                'message' => 'Data updated and recorded successfully.',
                'last_updated' => array(
                    'time' => $tm,
                    'date' => $dt
                )
            ));
        } catch (PDOException $e) {
            // Handle database errors
            echo json_encode(array(
                'error' => 'Database error: ' . $e->getMessage()
            ));
        }
    } else {
        // Send error response if required fields are missing
        echo json_encode(array(
            'error' => 'Missing required fields.'
        ));
    }
} else {
    // Fetch data from the smart_kitchen_container table
    $pdo = Database::connect();
    $sql = "SELECT * FROM smart_kitchen_container";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return JSON response with Rice Stock, Pulse Stock, last updated time, and last updated date
    echo json_encode(array(
        'Rice_Stock' => $row['Rice_Stock'],
        'Pulse_Stock' => $row['Pulse_Stock'],
        'last_updated' => array(
            'time' => $row['Time'],
            'date' => $row['Date']
        )
    ));

    // Disconnect from the database
    Database::disconnect();
}
?>
