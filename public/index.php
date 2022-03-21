<?php

use App\Component\Kernel\Kernel;
use App\Component\Kernel\Exception\NotFoundException;

ini_set('display_errors', true);
error_reporting(E_ALL);

// Check for PHP SQLite extension
if (!extension_loaded('sqlite3')) {
    trigger_error('SQLite extension is required', E_USER_ERROR);
}

require_once '../vendor/autoload.php';

try {
    
    (new Kernel(dirname(__DIR__)))->run();

} catch (NotFoundException $e) {

    header('HTTP/1.0 404 Not Found');
    echo $e->getMessage();

}
//  catch (\Throwable $e) {

//     header('HTTP/1.0 500 Internal Server Error');
//     echo "{$e->getMessage()} in file {$e->getFile()} at line {$e->getLine()}";

// }