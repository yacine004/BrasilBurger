<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<h2>ERROR $errno:</h2>";
    echo "<p>$errstr</p>";
    echo "<p>File: $errfile (line $errline)</p>";
});

echo "<h1>Diagnostic Symfony</h1>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// 1. Test répertoire courant
echo "<h2>1. Working Directory</h2>";
echo "<p>getcwd(): " . getcwd() . "</p>";
echo "<p>__DIR__: " . __DIR__ . "</p>";

// 2. Test fichiers essentiels
echo "<h2>2. Fichiers Essentiels</h2>";
$files = [
    '../config/bootstrap.php',
    '../src/Kernel.php',
    '../bin/console',
    '../composer.json',
    '../.env'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path) ? "✅ OUI" : "❌ NON";
    echo "<p>$file: $exists</p>";
}

// 3. Test variables d'environnement
echo "<h2>3. Variables d'Environnement</h2>";
echo "<pre>";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? "NOT SET") . "\n";
echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? "NOT SET") . "\n";
echo "DATABASE_URL: " . (isset($_ENV['DATABASE_URL']) || isset($_SERVER['DATABASE_URL']) ? "SET" : "NOT SET") . "\n";
echo "</pre>";

// 4. Test chargement bootstrap
echo "<h2>4. Test Bootstrap</h2>";
try {
    require __DIR__ . '/../config/bootstrap.php';
    echo "<p>✅ Bootstrap loaded successfully</p>";
} catch (Exception $e) {
    echo "<p>❌ Bootstrap failed: " . $e->getMessage() . "</p>";
}
?>
