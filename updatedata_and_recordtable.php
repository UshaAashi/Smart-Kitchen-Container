<?php
require 'database.php';

// Include the function definition
function generate_string_id($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
}

// Check if the POST data is not empty
if (!empty($_POST)) {
    // Check if all required fields are present in the POST data
    if (isset($_POST['Rice_Stock'], $_POST['Pulse_Stock'])) {
        // Assign POST data to variables
        $ID = $_POST['ID'];
        $Rice_Stock = $_POST['Rice_Stock'];
        $Pulse_Stock = $_POST['Pulse_Stock'];

        // Generate ID using the function
        //$ID = generate_string_id(); // Modify the length if needed

        // Set Board value to 'esp32_01'
       // $Board = 'esp32_01';

        // Get the current time and date
        date_default_timezone_set("Asia/Jakarta");
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
             // Generate ID using the function
            $ID = generate_string_id(); // Modify the length if needed

            // Set Board value to 'esp32_01'
            $Board = 'esp32_01';
            $sql = "INSERT INTO smart_kitchen_container_record (ID, Board, Rice_Stock, Pulse_Stock, Time, Date) VALUES (?, ?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute([$ID, $Board, $Rice_Stock, $Pulse_Stock, $tm, $dt]);

            // Disconnect from the database
            Database::disconnect();
            
            // Send success response
            echo "Data updated and recorded successfully.";
        } catch (PDOException $e) {
            // Handle database errors
            echo "Database error: " . $e->getMessage();
        }
    } else {
        // Send error response if required fields are missing
        echo "Error: Missing required fields.";
    }
} else {
    // Send error response if POST data is empty
    echo "Error: No data received.";
}
?>
