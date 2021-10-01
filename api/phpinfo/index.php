<?php

require_once '../../bin/Helpers.php';

$allowedHttpMethod = 'GET';

if (matchURI('phpinfo'))
{
    if ($_SERVER['REQUEST_METHOD'] === $allowedHttpMethod)
    {
        phpinfo();
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
