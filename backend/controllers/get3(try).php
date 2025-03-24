<?php
echo "Method: " . $_SERVER['REQUEST_METHOD'];
print_r($_GET);
print_r(json_decode(file_get_contents('php://input'), true));
?>