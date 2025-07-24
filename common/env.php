<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Ensure the .env file is in the correct directory
$dotenv = Dotenv::createImmutable(dirname(__DIR__) . '/common');
$dotenv->safeLoad(); // Prevents errors if .env is missing

// Load environment variables properly
if ($_ENV) {
    foreach ($_ENV as $key => $value) {
        if (!getenv($key)) { // Only set if not already set
            putenv("$key=$value");
            $_SERVER[$key] = $value;
            $_ENV[$key] = $value;
        }
    }
}

// Ensure important variables are set
$requiredEnvVars = ['AWS_S3_BUCKET', 'AWS_ACCESS_KEY', 'AWS_SECRET_KEY', 'AWS_REGION'];
foreach ($requiredEnvVars as $var) {
    if (!getenv($var) || getenv($var) === 'false') {
        error_log("Missing or incorrect env variable: $var");
    }
}

// Define Yii2 constants
defined('YII_DEBUG') or define('YII_DEBUG', filter_var(getenv('YII_DEBUG'), FILTER_VALIDATE_BOOLEAN));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');
