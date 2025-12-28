<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

echo "<h1>Debug Symfony index.php</h1>";

// Charger bootstrap
echo "<p>1. Loading bootstrap...</p>";
require_once dirname(__DIR__) . '/config/bootstrap.php';
echo "<p>✅ Bootstrap loaded</p>";

// Charger autoloader explicitement
echo "<p>2. Loading autoloader...</p>";
require_once dirname(__DIR__) . '/vendor/autoload.php';
echo "<p>✅ Autoloader loaded</p>";

// Essayer de créer le Kernel
echo "<p>3. Creating Kernel...</p>";
try {
    use App\Kernel;
    use Symfony\Component\HttpFoundation\Request;
    
    $kernel = new Kernel($_SERVER['APP_ENV'] ?? 'prod', (bool)($_SERVER['APP_DEBUG'] ?? false));
    echo "<p>✅ Kernel created</p>";
    echo "<p>Environment: " . $kernel->getEnvironment() . "</p>";
    echo "<p>Debug: " . ($kernel->isDebug() ? "YES" : "NO") . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Kernel error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}

// Essayer de charger la requête
echo "<p>4. Loading request...</p>";
try {
    $request = Request::createFromGlobals();
    echo "<p>✅ Request loaded</p>";
    echo "<p>Request method: " . $request->getMethod() . "</p>";
    echo "<p>Request URI: " . $request->getRequestUri() . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Request error: " . $e->getMessage() . "</p>";
}

echo "<h2>✅ Tous les composants fonctionnent!</h2>";
?>