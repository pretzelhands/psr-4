<?php

$configuration = json_decode(
    file_get_contents(__DIR__ . '/' . 'conductor.json'),
    true
);

$namespaces = $configuration['autoload']['psr-4'];

spl_autoload_register(function (string $class) use ($namespaces) {
    $prefix = strtok($class, '\\') . '\\';

    // We don't handle that namespace.
    // Return and hope some other autoloader handles it.
    if (!array_key_exists($prefix, $namespaces)) return;

    if (substr($class, 0, strlen($prefix)) == $prefix) {
        $relativeClass = substr($class, strlen($prefix));
    }
    $path = str_replace('\\', '/', $relativeClass) . '.php';

    require_once $baseDirectory . '/' . $path;
});
