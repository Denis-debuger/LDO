<?php
declare(strict_types=1);

/**
 * Symfony bridge:
 * If a Symfony app is available in /symfony and bridge mode is enabled,
 * delegate handling to Symfony Kernel.
 */
function symfony_bridge_handle_if_enabled(): void
{
    $enabled = (($_ENV['USE_SYMFONY'] ?? $_SERVER['USE_SYMFONY'] ?? '') === '1');
    if (!$enabled) {
        return;
    }

    $autoload = __DIR__ . '/../symfony/vendor/autoload.php';
    $kernelClass = '\\App\\Kernel';
    if (!is_file($autoload)) {
        return;
    }

    require_once $autoload;
    if (!class_exists($kernelClass)) {
        return;
    }

    $request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $kernel = new $kernelClass($_ENV['APP_ENV'] ?? 'prod', (($_ENV['APP_DEBUG'] ?? '0') === '1'));
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
    exit;
}
