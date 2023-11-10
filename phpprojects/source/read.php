<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET'); 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow, Authorization, X-Requested-With'); 

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    if(isset($_GET['id']))
    {
        $customer=getcustomer($_GET);
    }
    else{
    $FriendList = getFriendList();
    echo json_encode($FriendList);}
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    header('HTTP/1.0 405 Method Not Allowed'); 
    echo json_encode($data);
}
?>
