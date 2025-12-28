<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h1>Diagnostic Symfony</h1>";

try {
    // Tester si les fichiers de bootstrap existent
    $bootstrapPath = __DIR__ . '/../config/bootstrap.php';
    echo "<p>Bootstrap path: $bootstrapPath</p>";
    echo "<p>Bootstrap exists: " . (file_exists($bootstrapPath) ? "OUI" : "NON") . "</p>";
    
    if (file_exists($bootstrapPath)) {
        require_once $bootstrapPath;
        echo "<p>✅ Bootstrap loaded successfully</p>";
    }
    
    // Tester Kernel
    $kernelPath = __DIR__ . '/../src/Kernel.php';
    echo "<p>Kernel path: $kernelPath</p>";
    echo "<p>Kernel exists: " . (file_exists($kernelPath) ? "OUI" : "NON") . "</p>";
    
    echo "<h2>Environment Variables:</h2>";
    echo "<pre>";
    echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? "NOT SET") . "\n";
    echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? "NOT SET") . "\n";
    echo "DATABASE_URL: " . (isset($_ENV['DATABASE_URL']) ? "SET" : "NOT SET") . "\n";
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<h2>ERROR:</h2>";
    echo "<pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
}
?>
