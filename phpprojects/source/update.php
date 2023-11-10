<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT'); 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow, Authorization, X-Requested-With'); 
include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "PUT") {
   $inputData = json_decode(file_get_contents("php://input"), true); 

   if (empty($inputData)) {
        $updateuser = updateuser($_POST,$_GET); 
   } else {     
        $updateuser = updateuser($inputData,$_GET);
   }

   echo $updateuser; 
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    header('HTTP/1.0 405 Method Not Allowed'); 
    echo json_encode($data);
}
?>