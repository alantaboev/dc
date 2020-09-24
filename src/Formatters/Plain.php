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
    return array_reduce($diffTree, function ($acc, $node) use ($path) {
        switch ($node['type']) {
            case 'parent':
                $name = ($path === null) ? $node['name'] : "{$path}.{$node['name']}";
                $acc[] = getLines($node['children'], $name);
                break;
            case 'added':
                $acc[] = "Property '" . getName($node, $path) . "' was added with value: " . getValue($node['value']);
                break;
            case 'deleted':
                $acc[] = "Property '" . getName($node, $path) . "' was removed";
                break;
            case 'changed':
                $oldVal = getValue($node['beforeValue']);
                $newVal = getValue($node['afterValue']);
                $acc[] = "Property '" . getName($node, $path) . "' was updated. From $oldVal to $newVal";
                break;
            case 'unchanged':
                break;
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
        return $acc;
    });
}

function getName(array $node, $path): string
{
    if ($path === null) {
        return "{$node['key']}";
    }
    return "{$path}.{$node['key']}";
}

function getValue($value): string
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    return "'$value'";
}
