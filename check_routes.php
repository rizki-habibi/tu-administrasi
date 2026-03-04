<?php
// Extract all route() references from blade templates
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$routeNames = [];
foreach ($files as $file) {
    if ($file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    preg_match_all('/route\(["\']([^"\']+)["\']/', $content, $matches);
    foreach ($matches[1] as $name) {
        if (!isset($routeNames[$name])) $routeNames[$name] = [];
        $short = str_replace(['resources/views/', 'resources\\views\\'], '', str_replace('\\', '/', $file->getPathname()));
        if (!in_array($short, $routeNames[$name])) {
            $routeNames[$name][] = $short;
        }
    }
}
ksort($routeNames);
foreach ($routeNames as $name => $usedInFiles) {
    echo $name . " => " . implode(', ', $usedInFiles) . "\n";
}
