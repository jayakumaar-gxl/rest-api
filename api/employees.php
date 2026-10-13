<?php
require_once('../config/conn.php');
// https://chatgpt.com/share/6aba48b1-cc3c-83ee-9800-97f34b814b1f 27914 3237
//https://chatgpt.com/share/6aca31bf-b8bc-83e8-b312-d9dbeff89983

// JSON RESPONSE
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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, false, "Invalid email format", null);
    }

    //Email Verification - POST
    $stmt = mysqli_prepare($conn, "SELECT email,status from employees WHERE status=1 AND email=?  LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        sendResponse(409, true, "Email already exist");
    }
    mysqli_stmt_close($stmt);


    // Prepared statements method
    $stmt = mysqli_prepare($conn, "INSERT INTO employees (name,email,department,status) VALUES (?,?,?,1)");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $department);

    try {
        // Prepared statements method continuation
        mysqli_stmt_execute($stmt);
        sendResponse(201, true, "Employee Created Successfully");
    } catch (mysqli_sql_exception $e) {
        sendResponse(500, false, "Failed to create employee");
    }
}

// PUT
if ($_SERVER['REQUEST_METHOD'] == "PUT") {

    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id <= 0) {
        sendResponse(400, false, "Valid Employee ID is required", null);
    }

    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        sendResponse(400, false, "Invalid JSON format", null);
    }

    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $department = $input['department'] ?? '';
    $status = $input['status'] ?? null;
    if (empty($name) || empty($email) || empty($department) || !isset($input['status'])) {
        sendResponse(400, false, "Required employee fields are missing", null);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, false, "Invalid email format", null);
    }

    // Employee verification
    $stmt = mysqli_prepare($conn, "SELECT id FROM employees WHERE id = ?");
    if (!$stmt) {
        sendResponse(500, false, "Database query preparation failed", null);
    }
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        sendResponse(500, false, "Unable to verify employee", null);
    }
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
        mysqli_stmt_close($stmt);
        sendResponse(404, false, "Employee not found", null);
    }
    mysqli_stmt_close($stmt);

    // Duplicate email verification
    $stmt = mysqli_prepare( $conn, "SELECT id FROM employees WHERE status = 1 AND email = ? AND id != ?" );

    if (!$stmt) {
        sendResponse(500, false, "Database query preparation failed", null);
    }
    mysqli_stmt_bind_param($stmt, 'si', $email, $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        sendResponse(500, false, "Unable to verify employee email", null);
    }
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        sendResponse(409, false, "Email Already Exists", null);
    }

    mysqli_stmt_close($stmt);

    // Update employee
    $stmt = mysqli_prepare($conn, "UPDATE employees  SET name = ?, email = ?, department = ?, status = ?, updated_at = NOW()  WHERE id = ?");
    if (!$stmt) {
        sendResponse(500, false, "Database query preparation failed", null);
    }
    mysqli_stmt_bind_param($stmt, 'sssii', $name, $email, $department, $status, $id);

    try {
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            sendResponse(500, false, "Failed to update employee", null);
        }
        mysqli_stmt_close($stmt);
        sendResponse(200, true, $name . ' - employee updated successfully', null);
    } catch (mysqli_sql_exception $e) {
        mysqli_stmt_close($stmt);
        error_log("PUT employee update failed: " . $e->getMessage());
        sendResponse(500, false, "Failed to update employee", null);
    }
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    // sendResponse(200, true, "DELETE method reached");

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
    // $deleteStmt = mysqli_prepare($conn, "DELETE from employees where id=?");
    $deleteStmt = mysqli_prepare($conn, "UPDATE employees SET status = 0  where id=?");
    mysqli_stmt_bind_param($deleteStmt, 'i', $id);
    try {
        // *  Prepared statements method (continuation)
        mysqli_stmt_execute($deleteStmt);
        sendResponse(200, true, $name . ' - Employee deleted successfully');
    } catch (mysqli_sql_exception $e) {
        sendResponse(500, false, "Failed to delete employee");
    }
}

// GET
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $idProvided = isset($_GET['id']);
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if ($idProvided) {
        if ($id === null || $id === FALSE) {
            sendResponse(400, false, "Invalid Employee ID");
        }
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, department,status FROM employees WHERE id=?");
        if (!$stmt) {
            sendResponse(500, false, "Database query preparation failed", null);
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        // mysqli_stmt_execute($stmt);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            sendResponse(500, false, "Unable to fetch employee details", null);
        }
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id,name,email,department,status FROM employees ORDER BY id DESC");
        if (!$stmt) {
            sendResponse(500, false, "Database query preparation failed", null);
        }
        // mysqli_stmt_execute($stmt);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            sendResponse(500, false, "Unable to fetch employee details", null);
        }
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
