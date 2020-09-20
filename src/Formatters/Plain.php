<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

function render(array $differences)
{
    $lines = getLines($differences);
    return implode("\n", $lines);
}

function getLines(array $tree, $parentName = null): array
{
    $strings = array_reduce($tree, function ($acc, $node) use ($parentName) {
        switch ($node['type']) {
            case 'node':
                $name = ($parentName === null) ? $node['name'] : "{$parentName}.{$node['name']}";
                $acc[] = getLines($node['children'], $name);
                break;
            case 'added':
                $name = getName($node, $parentName);
                $value = getValue($node['value']);
                $acc[] = "Property '$name' was added with value: $value";
                break;
            case 'deleted':
                $name = getName($node, $parentName);
                $acc[] = "Property '$name' was removed";
                break;
            case 'changed':
                $name = getName($node, $parentName);
                $beforeValue = getValue($node['beforeValue']);
                $afterValue = getValue($node['afterValue']);
                $acc[] = "Property '$name' was updated. From $beforeValue to $afterValue";
                break;
            case 'unchanged':
                break;
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
        return $acc;
    });
    return flattenAll($strings);
}

function getName(array $node, $parentName): string
{
    if ($parentName === null) {
        return "{$node['key']}";
    }
    return "{$parentName}.{$node['key']}";
}

function getValue($value)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    return "'$value'";
}
