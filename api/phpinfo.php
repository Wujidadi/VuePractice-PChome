<?php

$allowedHttpMethod = 'GET';

if ($_SERVER['REQUEST_METHOD'] === $allowedHttpMethod)
{
    phpinfo();
}
else
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
}
    