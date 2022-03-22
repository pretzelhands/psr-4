<?php

$rootDirectory = __DIR__;

if (file_exists($rootDirectory . '/conductor.json')) {
    $configuration = json_decode(
        file_get_contents($rootDirectory . '/conductor.json'),
        true
    );
}

$namespaces = !empty($configuration['autoload']['psr-4']) ? $configuration['autoload']['psr-4'] : [];

spl_autoload_register(function (string $class) use ($namespaces, $rootDirectory) {
    $prefix = strtok($class, '\\') . '\\';

    // We don't handle that namespace.
    // Return and hope some other autoloader handles it.
    if (!array_key_exists($prefix, $namespaces)) return;

    $baseDirectory = $namespaces[$prefix];
    $relativeClass = (0 === strpos($class, $prefix)) ? substr($class, strlen($prefix)) : $class;
    $path = str_replace('\\', '/', $relativeClass) . '.php';

    require $rootDirectory . '/' . $baseDirectory . '/' . $path;
});
