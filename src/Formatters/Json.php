<?php

namespace Differ\Formatters\Json;

function render(array $differences): string
{
    return json_encode($differences, JSON_PRETTY_PRINT);
}
