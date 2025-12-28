<?php
// Capturer TOUTES les erreurs
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Buffer les erreurs
ob_start();

header('Content-Type: application/json');

$errors = [];
$success = [];

try {
    // 1. Vérifier vendor
    $autoload = dirname(__DIR__) . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        throw new Exception("vendor/autoload.php NOT FOUND at: $autoload");
    }
    $success[] = "vendor/autoload.php exists";
    
    // 2. Charger composer
    require_once $autoload;
    $success[] = "Composer autoload loaded";
    
    // 3. Charger bootstrap
    require_once dirname(__DIR__) . '/config/bootstrap.php';
    $success[] = "Bootstrap loaded";
    
    // 4. Créer kernel
    $kernel = new \App\Kernel($_SERVER['APP_ENV'] ?? 'prod', (bool)($_SERVER['APP_DEBUG'] ?? false));
    $success[] = "Kernel created (" . $kernel->getEnvironment() . ")";
    
    // 5. Créer request
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $success[] = "Request created";
    
    // 6. Essayer de traiter
    try {
        $response = $kernel->handle($request);
        $success[] = "Request handled successfully";
    } catch (Exception $e) {
        $errors[] = "Kernel handle failed: " . $e->getMessage();
        $errors[] = $e->getFile() . ":" . $e->getLine();
    }
    
} catch (Exception $e) {
    $errors[] = $e->getMessage();
    $errors[] = "File: " . $e->getFile() . " Line: " . $e->getLine();
    $errors[] = "Trace: " . substr($e->getTraceAsString(), 0, 500);
}

// Récupérer les erreurs PHP loggées
$logged_errors = ob_get_clean();
if ($logged_errors) {
    $errors[] = "PHP Errors: " . $logged_errors;
}

echo json_encode([
    'success' => $success,
    'errors' => $errors,
    'environment' => [
        'APP_ENV' => $_SERVER['APP_ENV'] ?? 'NOT SET',
        'APP_DEBUG' => $_SERVER['APP_DEBUG'] ?? 'NOT SET',
        'DATABASE_URL' => isset($_SERVER['DATABASE_URL']) ? 'SET' : 'NOT SET',
        'CWD' => getcwd(),
        'PHP_VERSION' => phpversion(),
    ]
], JSON_PRETTY_PRINT);
?>
