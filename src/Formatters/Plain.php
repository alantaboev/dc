<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

function render(array $differences): string
{
    $lines = getLines($differences);
    $simpleLines = flattenAll($lines);
    return implode("\n", $simpleLines);
}

function getLines(array $diffTree, string $path = null): array
{
    return array_map(function ($node) use ($path) {
        switch ($node['type']) {
            case 'parent':
                $name = getName($node, $path);
                return getLines($node['children'], $name);
            case 'added':
                return "Property '" . getName($node, $path) . "' was added with value: " . stringify($node['value']);
            case 'deleted':
                return "Property '" . getName($node, $path) . "' was removed";
            case 'changed':
                $oldVal = stringify($node['beforeValue']);
                $newVal = stringify($node['afterValue']);
                return "Property '" . getName($node, $path) . "' was updated. From $oldVal to $newVal";
            case 'unchanged':
                return [];
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
    }, $diffTree);
}

function getName(array $node, ?string $path): string
{
    if ($path === null) {
        return "{$node['key']}";
    }
    return "{$path}.{$node['key']}";
}

function stringify($value): string
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    return "'$value'";
}
