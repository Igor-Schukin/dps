<?php

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(404);
        echo "Method not supported";
    }


    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    if ($data === null) {
        http_response_code(400); 
        echo "Invalid JSON data";
    }


    // http_response_code(404);
    // $data = [
    //     "data" => 2,
    //     "message" => "Hello"
    // ];

    if ($data['name'] == "") {
        http_response_code(400);
        echo "Invalid name";
        return;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

/*


    // Retrieve the raw POST data
    $jsonData = file_get_contents('php://input');
    // Decode the JSON data into a PHP associative array
    $data = json_decode($jsonData, true);
    // Check if decoding was successful
    if ($data !== null) {
    // Access the data and perform operations
    $name = $data['name'];
    $age = $data['age'];
    // Perform further processing or respond to the request
    } else {
    // JSON decoding failed
    http_response_code(400); // Bad Request
    echo "Invalid JSON data";
    }

    $link = mysqli_connect("localhost", "student", "stud.net", "Net.Testing");
    mysqli_set_charset($link, "utf8");

    $result = mysqli_query($link, "
        INSERT INTO Comments (date, text, client) 
        VALUES (CURRENT_TIMESTAMP, '{$_POST["comment"]}', '{$_SERVER['REMOTE_ADDR']}')
    ");

    if ($result)
        print "<font color=Navy><b>Comment is successfully added into database</b></font><br>";
    else
        print "<font color=Red><b>Comment is not added into database due to error</b></font><br>";

    mysqli_close($link);

    // http_response_code(404);
    // $data = ;
    // header('Content-Type: application/json; charset=utf-8');
    // echo json_encode($data)
*/

?>
