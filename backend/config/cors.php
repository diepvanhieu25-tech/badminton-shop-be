<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    
    // Phải chỉ định rõ domain của Frontend, không được để '*'
    'allowed_origins' => ['http://localhost:3000'], 
    
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    
    // BẮT BUỘC TRUE để trình duyệt nhận Cookie
    'supports_credentials' => true, 
];
