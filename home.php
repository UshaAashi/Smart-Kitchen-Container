<!DOCTYPE HTML>
<html>
<head>
    <title>SMART KITCHEN CONTAINER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
          crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            text-align: center;
        }

        .topnav {
            overflow: hidden;
            background-color: #0c6980;
            color: white;
            font-size: 1.5rem;
            padding: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .display {
            background-color: #f2f2f2;
            border: 2px solid #0c6980;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            width: 200px;
        }

        .display h2 {
            margin-bottom: 10px;
        }

        .percentage {
            font-size: 2rem;
            font-weight: bold;
            color: #0c6980;
        }
    </style>
</head>

<body>
<div class="topnav">
    <h1>SMART KITCHEN CONTAINER</h1>
    <a href="recordtable.php">Open Record Table</a>
</div>

<div class="container">
    <div class="display" id="rice_display">
        <h2>Rice Stock</h2>
        <p class="percentage" id="rice_percentage">--%</p>
    </div>

    <div class="display" id="pulse_display">
        <h2>Pulse Stock</h2>
        <p class="percentage" id="pulse_percentage">--%</p>
    </div>

    <div class="display" id="last_updated_display">
        <h2>Last Updated</h2>
        <p id="last_updated">--</p>
    </div>
</div>

<script>
    // Function to fetch data using AJAX
    function getData() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                document.getElementById("rice_percentage").innerText = data.Rice_Stock + "%";
                document.getElementById("pulse_percentage").innerText = data.Pulse_Stock + "%";
                document.getElementById("last_updated").innerText = data.last_updated.date + " " + data.last_updated.time;
            }
        };
        xmlhttp.open("GET", "getdata.php", true);
        xmlhttp.send();
    }

    // Fetch data initially and then every 5 seconds
    getData();
    setInterval(getData, 5000);
</script>
</body>
</html>
