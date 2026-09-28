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

    // ? Check Employee Exist
    // * Normal value selection method
    // $checkQuery = "SELECT id,name from employees WHERE id='$id'";
    // $checkResult = mysqli_query($conn, $checkQuery);

    // * Prepared statements selection method
    $checkStmt = mysqli_prepare($conn, "SELECT id,name from employees WHERE id=?");
    mysqli_stmt_bind_param($checkStmt, "i", $id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);


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

    // *  Normal value selection method 
    // $query = "DELETE from employees where id='$id'";

    // *  Prepared statements method
    $deleteStmt = mysqli_prepare($conn, "DELETE from employees where id=?");
    mysqli_stmt_bind_param($deleteStmt, 'i', $id);
    try {
        // *  Normal value selection method (continuation)
        // $result = mysqli_query($conn, $query);

        // *  Prepared statements method (continuation)
        mysqli_stmt_execute($deleteStmt);

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

    // Normal value add method
    // $query = "UPDATE employees SET name='$name',email='$email',department='$department' WHERE id='$id'";
    // print_r($query);
    // exit;

    // Prepared statements method
    $stmt = mysqli_prepare($conn, "UPDATE employees SET name=?,email=?,department=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $department, $id);

    try {
        // Normal value add method continuation
        // $result = mysqli_query($conn, $query);

        // Prepared statements method continuation
        mysqli_stmt_execute($stmt);

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

    // Normal value add method
    // $query = "INSERT INTO employees (name,email, department) VALUES ('$name','$email','$department')";

    // Prepared statements method
    $stmt = mysqli_prepare($conn, "INSERT INTO employees (name,email,department) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $department);


    try {
        // Normal value add method continuation
        // $result = mysqli_query($conn, $query);

        // Prepared statements method continuation
        mysqli_stmt_execute($stmt);

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
    $idProvided = isset($_GET['id']);
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    // var_dump($id);
    // exit;

    if ($idProvided) {
        if ($id === null || $id === FALSE) {
            http_response_code(400);
            echo json_encode([
                "status"=>false,
                "message"=>"Invalid ID"
            ]);
            exit;
        }
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, department FROM employees WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }
    else {
       // prepared Statements
        // $query = "SELECT id, name, email, department FROM employees ORDER BY id DESC";
        $stmt = mysqli_prepare($conn, "SELECT id,name,email,department FROM employees ORDER BY id DESC");
        mysqli_stmt_execute($stmt);
    }
    $result = mysqli_stmt_get_result($stmt);

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

    // if ($id != null && count($employees) == 0) {
    if ($idProvided  && count($employees) == 0) {
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
// if ($_SERVER['REQUEST_METHOD'] == "GET") {
    //     $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    //     // Normal value add method
    //     // if ($id != null && $id != FALSE) {
    //     //     $query = "SELECT id, name, email, department FROM employees WHERE id=$id";
    //     // } else {
    //     //     $query = "SELECT id, name, email, department FROM employees ORDER BY id DESC";
    //     // }
    //     // $result = mysqli_query($conn, $query);


    //     // prepared Statements
    //     if ($id != null && $id != FALSE) {
    //         $stmt=mysqli_prepare($conn,"SELECT id, name, email, department FROM employees WHERE id=?");
    //         mysqli_stmt_bind_param($stmt,'i',$id);
    //         mysqli_stmt_execute($stmt);
    //     } else {
    //         // $query = "SELECT id, name, email, department FROM employees ORDER BY id DESC";
    //         $stmt=mysqli_prepare($conn,"SELECT id,name,email,department FROM employees ORDER BY id DESC");
    //         mysqli_stmt_execute($stmt);
    //     }
    //     $result = mysqli_stmt_get_result($stmt);

    //     if (!$result) {
    //         http_response_code(500); //Bad Request
    //         echo json_encode([
    //             "status" => false,
    //             "message" => "Database query failed"
    //         ]);
    //         exit;
    //     }

    //     $employees = [];
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $employees[] = $row;
    //     }

    //     if ($id != null && count($employees) == 0) {
    //         http_response_code(404); // Employee not found
    //         echo json_encode([
    //             'status' => false,
    //             'message' => 'Employee not found',
    //         ]);
    //     }

    //     http_response_code(200); //Request Successful
    //     echo json_encode([
    //         'status' => true,
    //         'data' => $employees,
    //     ]);
// }

mysqli_close($conn);
