<?php
// Run this locally to generate secure tokens
    $token = bin2hex(random_bytes(32)); // Your secret token
    echo "Token: $token\n";
    echo "Token Hash (bcrypt): " . password_hash($token, PASSWORD_BCRYPT) . "\n";
    echo "Token Hash (sha256): " . hash('sha256', $token) . "\n";

    $password = 'your-secure-password';
    echo "Password Hash: " . password_hash($password, PASSWORD_BCRYPT) . "\n";
?>
