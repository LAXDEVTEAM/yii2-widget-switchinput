<?php

/**
 * Test bootstrap file for SwitchInput widget tests
 */

// Register Composer autoloader if available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Simple autoloader for our classes
spl_autoload_register(function ($class) {
    // Handle kartik\switchinput namespace specifically
    if (strpos($class, 'kartik\\switchinput\\') === 0) {
        $className = str_replace('kartik\\switchinput\\', '', $class);
        $file = __DIR__ . '/../' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }

    // Convert namespace to file path for other classes
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Try to load from project root
    $file = __DIR__ . '/../' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    return false;
});

// Load mock classes for Yii2 dependencies
require_once __DIR__ . '/mocks/MockClasses.php';

// Define test environment constants
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
