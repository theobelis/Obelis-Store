<?php
return [
    // Configuración de la base de datos
    'database' => [
        'host' => DB_HOST,
        'user' => DB_USER,
        'pass' => DB_PASS,
        'name' => DB_NAME
    ],
    
    // Configuración del correo
    'email' => [
        'host' => HOST_SMTP,
        'user' => USER_SMTP,
        'pass' => PASS_SMTP,
        'port' => PUERTO_SMTP,
        'secure' => PHPMailer::ENCRYPTION_SMTPS
    ],
    
    // Configuración de la aplicación
    'app' => [
        'name' => TITLE,
        'url' => BASE_URL,
        'currency' => MONEDA,
        'items_per_page' => 12,
        'upload_path' => 'assets/images/productos/'
    ],
    
    // Configuración de seguridad
    'security' => [
        'session_lifetime' => 3600,
        'password_min_length' => 8,
        'allowed_attempts' => 3
    ],
    
    // Configuración de caché
    'cache' => [
        'enabled' => true,
        'lifetime' => 3600
    ]
];
