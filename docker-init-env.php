<?php

$envFile = '/var/www/html/.env';
$exampleFile = '/var/www/html/.env.example';

if (! file_exists($envFile)) {
    if (file_exists($exampleFile)) {
        copy($exampleFile, $envFile);
    } else {
        touch($envFile);
    }
}

$content = file_get_contents($envFile) ?: '';

// Check database host configuration
$dbHost = getenv('DB_HOST') ?: '';
$dbConnection = getenv('DB_CONNECTION') ?: '';

// If DB_HOST is unset or still contains a placeholder like <your_db_host>, fallback safely to SQLite
if (empty($dbHost) || str_contains($dbHost, '<') || str_contains($dbHost, 'your_db_host')) {
    echo "[Database] No valid remote MySQL host provided. Using built-in SQLite database.\n";
    $vars = [
        'APP_KEY' => getenv('APP_KEY') ?: null,
        'APP_URL' => getenv('APP_URL') ?: null,
        'APP_ENV' => getenv('APP_ENV') ?: null,
        'APP_DEBUG' => getenv('APP_DEBUG') ?: null,
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => '/var/www/html/database/database.sqlite',
        'DB_HOST' => '',
        'DB_PORT' => '',
        'DB_USERNAME' => '',
        'DB_PASSWORD' => '',
    ];
} else {
    echo "[Database] Connecting to MySQL host: {$dbHost}\n";
    $vars = [
        'APP_KEY' => getenv('APP_KEY') ?: null,
        'APP_URL' => getenv('APP_URL') ?: null,
        'APP_ENV' => getenv('APP_ENV') ?: null,
        'APP_DEBUG' => getenv('APP_DEBUG') ?: null,
        'DB_CONNECTION' => $dbConnection ?: 'mysql',
        'DB_HOST' => $dbHost,
        'DB_PORT' => getenv('DB_PORT') ?: '3306',
        'DB_DATABASE' => getenv('DB_DATABASE') ?: null,
        'DB_USERNAME' => getenv('DB_USERNAME') ?: null,
        'DB_PASSWORD' => getenv('DB_PASSWORD') ?: null,
    ];
}

foreach ($vars as $key => $value) {
    if ($value === null) {
        continue;
    }

    $pattern = "/^{$key}=.*/m";
    $replacement = "{$key}={$value}";

    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $replacement, $content);
    } else {
        $content = rtrim($content)."\n{$replacement}\n";
    }
}

file_put_contents($envFile, $content);
echo "Configuration synchronized successfully.\n";
