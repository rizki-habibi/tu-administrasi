<?php
$base = 'C:/laragon/www/ut adminisitrasi/resources/views/';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
$count = 0;
$missing = 0;
$results = [];

foreach ($it as $f) {
    if ($f->isFile() && str_ends_with($f->getFilename(), '.blade.php')) {
        $c = file_get_contents($f->getPathname());
        preg_match_all('/<form[^>]*method=["\']POST["\'][^>]*>/i', $c, $ms, PREG_OFFSET_CAPTURE);
        foreach ($ms[0] as $m) {
            $count++;
            $sub = substr($c, $m[1], 400);
            if (strpos($sub, '@csrf') === false) {
                $missing++;
                $rel = str_replace($base, '', str_replace('\\', '/', $f->getPathname()));
                $line = substr_count(substr($c, 0, $m[1]), "\n") + 1;
                echo "MISSING @csrf: {$rel} (line {$line})\n";
            }
        }
    }
}
echo "\nTotal POST forms: {$count}, Missing @csrf: {$missing}\n";
