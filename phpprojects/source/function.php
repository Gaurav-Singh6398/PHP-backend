<?php
require "../inc/db.php";
function getFriendList() {
    global $conn;
    $query = "SELECT * FROM friends";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Friends',
                'data' => $res,
            ];
            header("HTTP/1.0 200 OK");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No friends Found',
            ];
            header("HTTP/1.0 404 Not Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }
}
function error422($message) {
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

function storeuser($input) {
    global $conn;
    $name = mysqli_real_escape_string($conn, $input['name']);
    $password = mysqli_real_escape_string($conn, $input['password']);

    if (empty(trim($name))) {
        error422('Enter your name');
    } else if (empty(trim($password))) {
        error422('Enter your password');
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO user (Id, Password) VALUES (?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $name, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                $data = [
                    'status' => 201,
                    'message' => 'New User Created',
                ];
                header('Location: Login.html'); // Redirect to Login.html
                exit; // Ensure no further code execution after the redirect
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Internal Server Error',
                ];
                header('HTTP/1.0 500 Internal Server Error');
                echo json_encode($data);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode($data);
        }
    }
}

function updateuser($input,$params) {
    global $conn;
    if(!isset($params['id'])){
        error422('customer not found in url'); 
    }
    else{ 
    $id = mysqli_real_escape_string($conn, $params['id']); 
    $password = mysqli_real_escape_string($conn, $input['password']);}

    
    if (empty(trim($password))) {
        error422('Enter your password'); 
    } else {
        $query = $query = "UPDATE user SET Password = '$password' WHERE Id = '$id'"; // Corrected column names
        $request = mysqli_query($conn, $query);

        if ($request) {
            $data = [
                'status' => 200,
                'message' => 'Customer updated',
            ];
            header("HTTP/1.0 200 ok"); 
            echo json_encode($data);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    }
}

function deleteuser($params) {
    global $conn;

    if (!isset($params['id'])) {
        error422('Customer ID not found in URL');
    } else {
        $id = mysqli_real_escape_string($conn, $params['id']);
        
        $query = "DELETE FROM user WHERE Id = '$id'"; // Corrected the SQL query
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 204,
                'message' => 'Deleted',
            ];
            header("HTTP/1.0 204 No Content");
            echo json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'Customer not found',
            ];
            header("HTTP/1.0 404 Not Found");
            echo json_encode($data);
        }
    }
}
function getcustomer($input) {
    global $conn;

    if (!isset($input['id']) || !isset($input['password'])) {
        return error422("Both username and password are required.");
    }

    $user = mysqli_real_escape_string($conn, $input['id']);
    $password = mysqli_real_escape_string($conn, $input['password']);

    $query = "SELECT * FROM user WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $res = mysqli_fetch_assoc($result);
            $hashedPasswordFromDatabase = $res['Password'];

            if (password_verify($password, $hashedPasswordFromDatabase)) {
                // Passwords match - redirect to the home page
                session_start();
                $_SESSION['user'] = $user;
                $friends=getfriends($user);
                $data = [
                    'status' => 404,
                    'message' => 'Customer not found',
                    'data'=>$friends
                ];
                header("Location: getfriends.php");
                echo json_encode($data);
                exit; // Important to stop further execution
            } else {
                // Passwords do not match - redirect to the login page
                header("Location: Login.html");
                exit; // Important to stop further execution
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'Customer not found',
            ];
            header("HTTP/1.0 404 Not Found");
            echo json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }
}

function getuser($input) {
    global $conn;
    
    // Make sure to properly escape the input to prevent SQL injection
    $input = mysqli_real_escape_string($conn, $input);
    
    // Corrected SQL query and added proper column names in the SELECT statement
    $query = "SELECT froms, Message FROM friends WHERE to = '$input'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $res;
    } else {
        return false; // Handle any errors or return an appropriate value in case of an error
    }
}

    





?>




