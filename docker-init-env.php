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

// Check database configuration (supports direct DB_* vars, Railway native MYSQL* vars, and MYSQL_URL/DATABASE_URL)
$dbUrl = getenv('MYSQL_URL') ?: getenv('DATABASE_URL') ?: '';
$urlHost = '';
$urlPort = '';
$urlDatabase = '';
$urlUser = '';
$urlPass = '';

if (! empty($dbUrl)) {
    $parsed = parse_url($dbUrl);
    if (! empty($parsed['host'])) {
        $urlHost = $parsed['host'];
        $urlPort = isset($parsed['port']) ? (string) $parsed['port'] : '3306';
        $urlDatabase = ltrim($parsed['path'] ?? '', '/');
        $urlUser = $parsed['user'] ?? '';
        $urlPass = $parsed['pass'] ?? '';
    }
}

$dbHost = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: $urlHost ?: '';
$dbPort = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: $urlPort ?: '3306';
$dbDatabase = getenv('DB_DATABASE') ?: getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: $urlDatabase ?: '';
$dbUsername = getenv('DB_USERNAME') ?: getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: $urlUser ?: '';
$dbPassword = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: $urlPass ?: '';
$dbConnection = getenv('DB_CONNECTION') ?: 'mysql';

$publicDomain = getenv('RAILWAY_PUBLIC_DOMAIN') ?: getenv('RAILWAY_STATIC_URL') ?: '';
$appUrl = getenv('APP_URL') ?: ($publicDomain ? 'https://'.$publicDomain : null);

// If DB_HOST is unset or still contains a placeholder like <your_db_host>, fallback safely to SQLite
if (empty($dbHost) || str_contains($dbHost, '<') || str_contains($dbHost, 'your_db_host')) {
    echo "[Database] No remote MySQL host provided. Using built-in SQLite database.\n";
    $vars = [
        'APP_KEY' => getenv('APP_KEY') ?: null,
        'APP_URL' => $appUrl,
        'APP_ENV' => getenv('APP_ENV') ?: null,
        'APP_DEBUG' => getenv('APP_DEBUG') ?: null,
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => '/var/www/html/database/database.sqlite',
        'DB_HOST' => '',
        'DB_PORT' => '',
        'DB_USERNAME' => '',
        'DB_PASSWORD' => '',
        'SESSION_DRIVER' => 'file',
        'CACHE_STORE' => 'file',
        'LOG_CHANNEL' => 'stderr',
    ];
} else {
    echo "[Database] Connecting to persistent MySQL host: {$dbHost}:{$dbPort} (Database: {$dbDatabase})\n";
    $vars = [
        'APP_KEY' => getenv('APP_KEY') ?: null,
        'APP_URL' => $appUrl,
        'APP_ENV' => getenv('APP_ENV') ?: null,
        'APP_DEBUG' => getenv('APP_DEBUG') ?: null,
        'DB_CONNECTION' => $dbConnection,
        'DB_HOST' => $dbHost,
        'DB_PORT' => $dbPort,
        'DB_DATABASE' => $dbDatabase ?: 'railway',
        'DB_USERNAME' => $dbUsername ?: 'root',
        'DB_PASSWORD' => $dbPassword,
        'SESSION_DRIVER' => getenv('SESSION_DRIVER') ?: 'file',
        'CACHE_STORE' => getenv('CACHE_STORE') ?: 'file',
        'LOG_CHANNEL' => getenv('LOG_CHANNEL') ?: 'stderr',
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
