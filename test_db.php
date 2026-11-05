<?php
// TIMEYRAH Live Server Diagnostic Tool
// Upload to htdocs/ and open http://watchestest.infy.click/test_db.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>TIMEYRAH — InfinityFree Diagnostic Check</h2>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>Current Directory:</strong> " . __DIR__ . "<br><br>";

// 1. Check .env file
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    echo "<span style='color:red;'>❌ CRITICAL: .env file NOT FOUND in " . __DIR__ . "!</span><br>";
} else {
    echo "<span style='color:green;'>✅ .env file found.</span><br>";
    $envContent = file_get_contents($envPath);
    preg_match('/DB_CONNECTION=(.*)/', $envContent, $mConn);
    preg_match('/DB_HOST=(.*)/', $envContent, $mHost);
    preg_match('/DB_PORT=(.*)/', $envContent, $mPort);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $mDb);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $mUser);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $mPass);

    $dbConn = trim($mConn[1] ?? 'not set');
    $dbHost = trim($mHost[1] ?? 'not set');
    $dbPort = trim($mPort[1] ?? '3306');
    $dbName = trim($mDb[1] ?? 'not set');
    $dbUser = trim($mUser[1] ?? 'not set');
    $dbPass = trim($mPass[1] ?? '');

    echo "<strong>DB_CONNECTION:</strong> " . htmlspecialchars($dbConn) . "<br>";
    echo "<strong>DB_HOST:</strong> " . htmlspecialchars($dbHost) . "<br>";
    echo "<strong>DB_PORT:</strong> " . htmlspecialchars($dbPort) . "<br>";
    echo "<strong>DB_DATABASE:</strong> " . htmlspecialchars($dbName) . "<br>";
    echo "<strong>DB_USERNAME:</strong> " . htmlspecialchars($dbUser) . "<br>";

    if ($dbConn === 'sqlite') {
        echo "<br><span style='color:red; font-size:16px;'>❌ ERROR: DB_CONNECTION is set to 'sqlite'! InfinityFree requires MySQL (Host: sql312.byetcluster.com).</span><br>";
    } elseif ($dbHost === '127.0.0.1' || $dbHost === 'localhost') {
        echo "<br><span style='color:red; font-size:16px;'>❌ ERROR: DB_HOST is set to '{$dbHost}'! InfinityFree MySQL is NOT on localhost. Set DB_HOST=sql312.byetcluster.com</span><br>";
    } else {
        echo "<br>Testing connection to <strong>{$dbHost}</strong> (5 second timeout)...<br>";
        try {
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
            $start = microtime(true);
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $time = round((microtime(true) - $start) * 1000, 2);
            echo "<span style='color:green; font-size:16px;'>✅ SUCCESS: Connected to MySQL in {$time}ms!</span><br><br>";

            // Check tables
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "<strong>Existing Tables in Database (" . count($tables) . "):</strong> " . implode(', ', $tables) . "<br><br>";

            if (in_array('page_sections', $tables)) {
                echo "<span style='color:green;'>✅ 'page_sections' table exists.</span><br>";
            } else {
                echo "<span style='color:orange;'>⚠️ 'page_sections' table NOT found in database.</span><br>";
            }
        } catch (\Exception $e) {
            echo "<span style='color:red; font-size:16px;'>❌ MySQL Connection Failed: " . htmlspecialchars($e->getMessage()) . "</span><br>";
        }
    }
}

// 2. Check public/build/manifest.json
echo "<br><hr><br>";
$manifestPath = __DIR__ . '/public/build/manifest.json';
if (file_exists($manifestPath)) {
    echo "<span style='color:green;'>✅ Frontend assets manifest exists: public/build/manifest.json</span><br>";
} else {
    echo "<span style='color:red;'>❌ Frontend assets manifest NOT FOUND: public/build/manifest.json</span><br>";
}

// 3. Check storage directory permissions
$storagePath = __DIR__ . '/storage/framework/views';
if (is_writable(__DIR__ . '/storage')) {
    echo "<span style='color:green;'>✅ storage/ directory is writable.</span><br>";
} else {
    echo "<span style='color:red;'>❌ storage/ directory is NOT writable! Please set chmod 755 (or 777) on storage/.</span><br>";
}

