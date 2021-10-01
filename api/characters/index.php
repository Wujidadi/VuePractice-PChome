<?php

require_once '../../bin/Helpers.php';
require_once '../../bin/DbPeople.php';

$allowedHttpMethod = 'GET';

if (matchURI('characters'))
{
    if ($_SERVER['REQUEST_METHOD'] === $allowedHttpMethod)
    {
        $page  = (isset($_GET['p']) && $_GET['p'] > 0) ? $_GET['p'] : 1;     // 當前頁碼
        $limit = (isset($_GET['c']) && $_GET['c'] > 0) ? $_GET['c'] : 10;    // 每頁筆數
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM Characters ORDER BY `No` LIMIT {$limit} OFFSET {$offset};";
        $query = $dbConn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($result, 320);
    }
    else
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    }
}
else
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
