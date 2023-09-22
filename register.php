<?php

    function fault($message) {
        http_response_code(400);
        exit($message);        
    }

    $server = 'localhost';
    $database = 'dps';
    $table = 'students';
    $user = 'dps';
    $password = 'gaudeamus';

    // Request method must by POST

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') fault("Method not supported");

    // Read JSON from body

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    if ($data === null) fault("Invalid JSON document");

    // connect to DB

    $link = mysqli_connect($server, $user, $password, $database);
    mysqli_set_charset($link, "utf8");

    // Check if user exists

    $result = mysqli_query($link, "
        SELECT COUNT(*) AS num_rows 
        FROM {$table} 
        WHERE name='{$data["name"]}' AND  surname='{$data["surname"]}' AND  group_id='{$data["group"]}'
        LIMIT 1;
    ");
    
    $row = mysqli_fetch_array($result);
    if($row["num_rows"] > 0) {
        mysqli_close($link);
        fault( "User already exists");
    }

    // Insert new record to DB

    $result = mysqli_query($link, "
        INSERT INTO {$table} (group_id, name, surname, school, year, email, phone) 
        VALUES ('{$data["group"]}', '{$data["name"]}', '{$data["surname"]}', '{$data["school"]}', '{$data["year"]}', '{$data["email"]}', '{$data["phone"]}')
    ");

    mysqli_close($link);

    if (!$result) fault("Fault to insert new record to DB");

    echo "OK";

?>
