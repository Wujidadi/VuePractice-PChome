<?php

chdir(__DIR__);

$dbName = '../storage/sqlite/People.db';

try
{
    // $dbConn = new SQLite3($dbName);
    $dbConn = new PDO("sqlite:{$dbName}");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbConn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

    // echo "SQLite database connected";
}
catch (Throwable $ex)
{
    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMsg  = $ex->getMessage();
    echo "{$exType} ({$exCode}): {$exMsg}";
}
