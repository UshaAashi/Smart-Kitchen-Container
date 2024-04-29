<!DOCTYPE HTML>
<html>
<head>
    <title>SMART KITCHEN CONTAINER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            text-align: center;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .topnav {
            overflow: hidden;
            background-color: #0c6980;
            color: white;
            font-size: 1.5rem;
            padding: 10px;
        }

        h3 {
            color: #0c6980;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #0c6980;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn-group {
            margin-top: 20px;
        }

        .button {
            background-color: #0c6980;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        select {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div class="topnav">
    <h3>SMART KITCHEN CONTAINER</h3>
</div>

<h3>Recorded Data Table</h3>

<table>
    <thead>
    <tr>
        <th>NO</th>
        <th>ID</th>
        <th>BOARD</th>
        <th>RICE_STOCK</th>
        <th>PULSE_STOCK</th>
        <th>TIME</th>
        <th>DATE (dd-mm-yyyy)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Include database connection and retrieve data
    include 'database.php';
    $pdo = Database::connect();
    $sql = 'SELECT * FROM smart_kitchen_container_record ORDER BY Date DESC, Time DESC';
    $num = 0;
    foreach ($pdo->query($sql) as $row) {
        $num++;
        echo '<tr>';
        echo '<td>' . $num . '</td>';
        echo '<td>' . $row['ID'] . '</td>';
        echo '<td>' . $row['Board'] . '</td>';
        echo '<td>' . $row['Rice_Stock'] . '</td>';
        echo '<td>' . $row['Pulse_Stock'] . '</td>';
        echo '<td>' . $row['Time'] . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($row['Date'])) . '</td>';
        echo '</tr>';
    }
    Database::disconnect();
    ?>
    </tbody>
</table>

<div class="btn-group">
    <!-- Add hyperlink to home.php -->
    <button class="button" onclick="window.location.href='home.php'">Back to Home</button>
</div>

<!-- Display last updated date and time -->
<?php
    include 'database.php';
    $pdo = Database::connect();
    $sql = 'SELECT Date, Time FROM smart_kitchen_container ORDER BY Date DESC, Time DESC LIMIT 1';
    $stmt = $pdo->query($sql);
    $last_updated = $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
?>
<p>Last Updated: <?php echo date('d-m-Y', strtotime($last_updated['Date'])) . ' ' . $last_updated['Time']; ?></p>

</body>
</html>

