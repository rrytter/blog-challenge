<?php

/**
 * SQLite Driver
 * 
 * @link https://www.php.net/manual/ref.pdo-sqlite.connection.php
 */
return ['sqlite:' . dirname(__DIR__). '/var/database_enhanced.sqlite'];

/**
 * MySQL Driver
 * 
 * @link https://www.php.net/manual/ref.pdo-mysql.connection.php
 */
// return ['mysql:host=localhost;port=3306;dbname=blog;charset=utf8', $username = 'root', $password = ''];