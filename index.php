<?php
// Allow all origins by using "*"
$allowedOrigin = "*";

// Set CORS headers for preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Headers for CORS preflight request
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 86400"); // Cache for 1 day
    
    // Return a 200 OK response for OPTIONS requests
    http_response_code(200);
    exit();
}

// Set CORS headers for actual requests
header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// Function to validate the token
function validate_token($token) {
    // For demonstration, the token is hard-coded. Replace with your logic.
    return $token === "b9e9d7516943744aed44d0be39217694a92784ce737e07c9e12397eca99fded0";
}

// Get the token from the headers
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

// Validate the token
if (!$token || !validate_token($token)) {
    http_response_code(403);
    echo json_encode(["message" => "Forbidden: Invalid Token"]);
    exit();
}

// Load the array from the /fontawesome/icons.php file
$data = include './fontawesome/icons.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Return the array as JSON response
    echo json_encode($data);
} else {
    // Method not allowed response
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
