<?php
header('Content-Type:application/json');
require_once('../config/conn.php');




// POST
if($_SERVER['REQUEST_METHOD']=="POST"){
    $input=json_decode(file_get_contents("php://input"),true);

    if($input==null){
        http_response_code(400);
        echo json_encode([
            'status'=>false,
            'message'=>'Invalid JSON Format',
        ]);
        exit;
    }

    $name=$input['name']??'';
    $email=$input['email']??'';
    $department=$input['department']??'';
   
    // echo json_encode([
    // 'name'=>$name,
    // 'email'=>$email,
    // 'department'=>$department,
    // 'status'=>true,
    // 'data'=>$input,  
    // 'message'=>'POST Method received',
    // ]);

    if(empty($name)|| empty($email)|| empty($department)){
        http_response_code(400);
        echo json_encode([
            'status' => false,
            // 'message' => $e->getMessage(),
            'message' => 'Name, Email or Department value is missing',
        ]);
        exit;
    }

     $query="INSERT INTO employees (name,email, department) VALUES 
    ('$name','$email','$department')";
    // $result=mysqli_query($conn, $query);

    try {
         $result=mysqli_query($conn, $query);
        http_response_code(201);
        echo json_encode([
            'status' => true,
            'message' => 'User Inserted Successfully',
        ]);        
    } catch (mysqli_sql_exception $e){
        http_response_code(500);
        echo json_encode([
            'status' => false,
            // 'message' => $e->getMessage(),
            'message' => 'User Insertion Failed',
        ]);
        // exit;
    }

}



// GET
$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);

if($id != null && $id != FALSE){ 
    $query="SELECT id, name, email, department FROM employees WHERE id=$id";
}
else{
    $query="SELECT id, name, email, department FROM employees ORDER BY id DESC";
}
$result=mysqli_query($conn, $query);

if(!$result){
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database query failed"
    ]);
    exit;
}


$employees = [];
while($row=mysqli_fetch_assoc($result)){
    $employees[]=$row;
}

if($id!= null && count($employees)==0){
    http_response_code(404);
    echo json_encode([
        'status'=>false,
        'message'=>'Employee not found',
    ]);
}

echo json_encode([
    'status'=>true,
    'data'=>$employees,
]);

mysqli_close($conn);

