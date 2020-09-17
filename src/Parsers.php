<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, $format)
{
    if ($format === 'json') {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    } elseif ($format === 'yaml' || $format === 'yml') {
        $result = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        return (array) $result; // Возврат с преобразованием объекта к ассоциативному массиву
    } else {
        throw new \Exception("Invalid format: $format. Data must be in JSON or YAML format");
    }
}
