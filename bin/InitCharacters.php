<?php

require_once 'DbPeople.php';

try
{
    $sql = <<<SQL
    CREATE TABLE Characters (
        No        CHAR(4)      PRIMARY KEY  NOT NULL,
        Id        VARCHAR(30)  UNIQUE       NOT NULL,
        Name      VARCHAR(30)               NOT NULL,
        Gender    SMALLINT                  NOT NULL,
        Birthday  CHAR(5),
        Title     VARCHAR(30),
        Unit      VARCHAR(50),
        Email     VARCHAR(255),
        Mobile    CHAR(13),
        Address   VARCHAR(255)
    );
    SQL;

    $dbConn->exec($sql);
}
catch (Throwable $ex)
{
    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMsg  = $ex->getMessage();
    echo "{$exType} ({$exCode}): {$exMsg}";
}
