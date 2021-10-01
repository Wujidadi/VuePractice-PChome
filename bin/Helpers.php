<?php

function matchURI($uri)
{
    $requestURI = preg_replace('/\?[^\?]*$/', '', $_SERVER['REQUEST_URI']);
    return (preg_match('/' . $uri . '$/',             $requestURI) ||
            preg_match('/' . $uri . '\/$/',           $requestURI) ||
            preg_match('/' . $uri . '\/index\.php$/', $requestURI));
}
