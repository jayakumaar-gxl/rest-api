<?php
require_once('../config/conn.php');

// DELETE
if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $input = json_decode(file_get_contents('php://input'), true);

    
    // ID Validation
    if ($id === false || $id === null) {
        http_response_code(400); //Data not found
        echo json_encode([
            'status' => false,
            'message' => "User value is missed",
        ]);
        exit;
    }

    // Check Employee Exist
    $checkQuery = "SELECT id,name from employees WHERE id='$id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        http_response_code(404); //Data not found
        echo json_encode([
            'status' => false,
            'message' => 'Employee not found'
        ]);
        exit;
    }

    $employee = mysqli_fetch_assoc($checkResult);
    $name = $employee['name'];


    $query = "DELETE from employees where id='$id'";
    try {
        $result = mysqli_query($conn, $query);
        http_response_code(200); //Successful Delete
        echo json_encode([
            'status' => true,
            'message' => $name . ' details deleted successfully',
        ]);
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); //Bad Request
        echo json_encode([
            'status' => false,
            'message' => 'User Deletion Failed'
        ]);
    }

    // print_r($input);
    // exit;

    // echo json_encode([
    //     'status'=>true,
    //     'message'=>'DELETE REQUEST RECEIVED'
    // ]);
}

// PUT
if ($_SERVER['REQUEST_METHOD'] == "PUT") {

    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    $input = json_decode(file_get_contents("php://input"), true);

    if ($input == null) {
        http_response_code(400); //Invalid json format
        echo json_encode(
            [
                "status" => false,
                "message" => "Invalid json format"
            ]
        );
        exit;
    }

    $name = $input['name'];
    $email = $input['email'];
    $department = $input['department'];

    if (empty($name) || empty($email) || empty($department)) {
        http_response_code(400); //Data not found
        echo json_encode([
            'status' => false,
            'message' => 'Name, Email or Department values is missed'
        ]);
        exit;
    }


    $query = "UPDATE employees SET name='$name',email='$email',department='$department' WHERE id='$id'";
    // print_r($query);
    // exit;
    try {
        $result = mysqli_query($conn, $query);
        http_response_code(200); //Successful Update
        echo json_encode([
            'status' => true,
            'message' => $name . ' details updated successfully',
        ]);
        // exit;
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); //Bad Request
        echo json_encode([
            'status' => false,
            'message' => 'User Update Failed',
        ]);
        // exit
    }

    // echo json_encode([
    //     'status'=>false,
    //     'message'=>'PUT REQUEST RECEIVED'
    // ]);
}

// POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if ($input == null) {
        http_response_code(400); //Invalid Json Format
        echo json_encode([
            'status' => false,
            'message' => 'Invalid JSON Format',
        ]);
        exit;
    }

    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $department = $input['department'] ?? '';
    // echo json_encode([
    // 'name'=>$name,
    // 'email'=>$email,
    // 'department'=>$department,
    // 'status'=>true,
    // 'data'=>$input,  
    // 'message'=>'POST Method received',
    // ]);

    if (empty($name) || empty($email) || empty($department)) {
        http_response_code(400); //Data Not Found
        echo json_encode([
            'status' => false,
            // 'message' => $e->getMessage(),
            'message' => 'Name, Email or Department value is missing',
        ]);
        exit;
    }

    $query = "INSERT INTO employees (name,email, department) VALUES 
    ('$name','$email','$department')";
    // $result=mysqli_query($conn, $query);

    try {
        $result = mysqli_query($conn, $query);
        http_response_code(201); //Successful Creation
        echo json_encode([
            'status' => true,
            'message' => 'User Inserted Successfully',
        ]);
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); //Bad Request
        echo json_encode([
            'status' => false,
            // 'message' => $e->getMessage(),
            'message' => 'User Insertion Failed',
        ]);
        // exit;
    }
}

// GET
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if ($id != null && $id != FALSE) {
        $query = "SELECT id, name, email, department FROM employees WHERE id=$id";
    } else {
        $query = "SELECT id, name, email, department FROM employees ORDER BY id DESC";
    }
    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(500); //Bad Request
        echo json_encode([
            "status" => false,
            "message" => "Database query failed"
        ]);
        exit;
    }

    $employees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }

    if ($id != null && count($employees) == 0) {
        http_response_code(404); // Employee not found
        echo json_encode([
            'status' => false,
            'message' => 'Employee not found',
        ]);
    }

    http_response_code(200); //Request Successful
    echo json_encode([
        'status' => true,
        'data' => $employees,
    ]);
}

mysqli_close($conn);
