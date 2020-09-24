<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $format): array
{
    if ($format === 'json') {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
    if ($format === 'yaml' || $format === 'yml') {
        return Yaml::parse($content);
    }
    throw new \Exception("Invalid format: $format. Data must be in JSON or YAML format");
}
