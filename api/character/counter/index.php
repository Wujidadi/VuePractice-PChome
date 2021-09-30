<?php

require_once '../../../bin/DbPeople.php';

$allowedHttpMethod = 'GET';

if ($_SERVER['REQUEST_METHOD'] === $allowedHttpMethod)
{
    $sql = "SELECT COUNT(No) AS Counter FROM Characters;";
    $query = $dbConn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($result[0], 320);
}
else
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
