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

// Synchronize environment variables safely into .env
$vars = [
    'APP_KEY' => getenv('APP_KEY') ?: null,
    'APP_URL' => getenv('APP_URL') ?: null,
    'APP_ENV' => getenv('APP_ENV') ?: null,
    'APP_DEBUG' => getenv('APP_DEBUG') ?: null,
    'DB_CONNECTION' => getenv('DB_CONNECTION') ?: null,
    'DB_HOST' => getenv('DB_HOST') ?: null,
    'DB_PORT' => getenv('DB_PORT') ?: null,
    'DB_DATABASE' => getenv('DB_DATABASE') ?: null,
    'DB_USERNAME' => getenv('DB_USERNAME') ?: null,
    'DB_PASSWORD' => getenv('DB_PASSWORD') ?: null,
];

foreach ($vars as $key => $value) {
    if ($value === null || $value === '') {
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
