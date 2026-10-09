<?php
require_once('../config/conn.php');
// https://chatgpt.com/share/6aba48b1-cc3c-83ee-9800-97f34b814b1f 27914 3237


function sendResponse($responseCode, $status, $message, $data = null)
{
    http_response_code($responseCode);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $input = json_decode(file_get_contents('php://input'), true);

    // ID Validation
    if ($id === false || $id === null) {
        sendResponse(400, false, "Employee id is missing or invalid");
    }

    // ? Check Employee Exist
    // * Prepared statements selection method
    $checkStmt = mysqli_prepare($conn, "SELECT id,name from employees WHERE id=?");
    mysqli_stmt_bind_param($checkStmt, "i", $id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);


    if (mysqli_num_rows($checkResult) == 0) {
        sendResponse(4004, false, "Employee not found");
    }

    $employee = mysqli_fetch_assoc($checkResult);
    $name = $employee['name'];

    // *  Prepared statements method
    $deleteStmt = mysqli_prepare($conn, "DELETE from employees where id=?");
    mysqli_stmt_bind_param($deleteStmt, 'i', $id);
    try {
        // *  Prepared statements method (continuation)
        mysqli_stmt_execute($deleteStmt);
        sendResponse(200, true, $name . ' - Employee deleted successfully');
    } catch (mysqli_sql_exception $e) {
        sendResponse(500, false, "Failed to delete employee");
    }
}

// PUT
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    $input = json_decode(file_get_contents("php://input"), true);
    if ($input == null) {
        sendResponse(400, false, "Invalid json format");
    }

    $name = $input['name'];
    $email = $input['email'];
    $department = $input['department'];
    if (empty($name) || empty($email) || empty($department)) {
        sendResponse(400, false, "User - Name, Email or Department values are missing");
    }

    // Prepared statements method
    $stmt = mysqli_prepare($conn, "UPDATE employees SET name=?,email=?,department=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $department, $id);

    try {
        // Prepared statements method continuation
        mysqli_stmt_execute($stmt);
        sendResponse(200, true, $name . ' - employee updated successfully');
    } catch (mysqli_sql_exception $e) {
        sendResponse(500, false, 'Failed to update employee');
    }
}

// POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    if ($input == null) {
        sendResponse(400, false, "Invalid JSON Format");
    }

    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $department = $input['department'] ?? '';


    if (empty($name) || empty($email) || empty($department)) {
        sendResponse(400, false, "Name, Email or Department value is missing");
    }

    // Prepared statements method
    $stmt = mysqli_prepare($conn, "INSERT INTO employees (name,email,department) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $department);

    try {
        // Prepared statements method continuation
        mysqli_stmt_execute($stmt);
        sendResponse(201, true, "Employee Created Successfully");
    } catch (mysqli_sql_exception $e) {
        sendResponse(500, false, "Failed to create employee");
    }
}

// GET
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $idProvided = isset($_GET['id']);
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    $page=filter_input(INPUT_GET,'page',FILTER_VALIDATE_INT);
    $limit=filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);

    if($page == false || $page== null || $page<1){
        sendResponse(400,false,"Invalid Page Number");
    }
    if($limit == false || $limit == null || $limit<1){
        sendResponse(400,false,"Invalid Limit");
    }
    
    if ($idProvided) {
        if ($id === null || $id === FALSE) {
            sendResponse(400, false, "Invalid Employee ID");
        }
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, department FROM employees WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    } else {
        $offset=($page-1)*$limit;
        $stmt = mysqli_prepare($conn, "SELECT id,name,email,department FROM employees ORDER BY id DESC LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
        mysqli_stmt_execute($stmt);
    }
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        sendResponse(500, false, "Database query failed");
    }

    $employees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }

    if ($idProvided  && count($employees) == 0) {
        sendResponse(404, false, "Employee not found");
    }
    sendResponse(200, true, "Employees Fetched Successfully", $employees);
}
mysqli_close($conn);

// FALLBACK METHOD
sendResponse(405, false, "Methods not allowed");
