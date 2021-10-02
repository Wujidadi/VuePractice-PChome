<?php

require_once '../../bin/DbPeople.php';

$allowedHttpMethod = 'GET';

if ($_SERVER['REQUEST_METHOD'] === $allowedHttpMethod)
{
    $sql = "SELECT MAX(No) AS MaxNo FROM Characters;";
    $query = $dbConn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $maxNo = $result[0]['MaxNo'];
    $nextNo = str_pad((int) $maxNo + 1, 4, '0', STR_PAD_LEFT);
    $data = [ 'NextNo' => $nextNo ];

    header('Content-Type: application/json');
    echo json_encode($data, 320);
}
else
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
}
