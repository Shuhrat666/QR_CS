<?php
declare(strict_types=1);

use Route\Route;

// require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
    Route::handleBot(); 
} else {
    Route::handleWeb(); 
}
