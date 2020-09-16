<?php

namespace Differ\Parser;

function parse(string $content, $format)
{
    if ($format === 'json') {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
    throw new \Exception("Invalid format: $format. Data must be in JSON or YAML format");
}
