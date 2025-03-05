<?php

// Run the Artisan command and capture the output
$output = shell_exec('php artisan folio:list');

// Split the output into lines
$lines = explode(PHP_EOL, $output);

// Initialize an array to store the routes
$routes = [];

// Loop through each line and extract the routes
foreach ($lines as $line) {
    // Match lines that contain routes
    if (preg_match('/GET\s+\/([^\s]+)/', $line, $matches)) {
        $routes[] = $matches[1];
    }
}

// Print the routes array
print_r($routes);
