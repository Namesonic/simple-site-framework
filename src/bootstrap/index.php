<?php

use SimpleSite\View\View;

/**
 * Helper function to get a View object that is ready to load the specified page and param substitution
 *
 * @param string $page
 * @param array $params
 * @return View
 */
function view(string $page = '', array $params = []): View
{
    return new View($page, $params);
}

function config(string $key): String
{
    global $config;

    return $config[$key] ?? '';
}