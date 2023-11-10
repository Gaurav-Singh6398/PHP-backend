<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST'); 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow, Authorization, X-Requested-With'); 

include('function.php'); // Include the file where your functions are defined

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true); 

    if (empty($inputData)) {
        // Use the function from your included 'function.php' file
        $storeuser = storeuser($_POST); 
    } else {
        // Use the function from your included 'function.php' file
        $storeuser = storeuser($inputData);
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    header('HTTP/1.0 405 Method Not Allowed'); 
    echo json_encode($data);
}
?>




