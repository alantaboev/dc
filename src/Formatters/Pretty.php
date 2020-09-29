<?php

namespace Differ\Formatters\Pretty;

function render(array $differences): string
{
    $lines = getLines($differences);
    $implodeLines = implode("\n", $lines);
    return "{\n$implodeLines\n}\n";
}

function getLines(array $diffTree, int $depth = 0): array
{
    $indent = '  ' . str_repeat('    ', $depth);
    return array_reduce($diffTree, function ($acc, $node) use ($indent, $depth) {
        switch ($node['type']) {
            case 'parent':
                $children = getLines($node['children'], $depth + 1);
                $acc[] = "$indent  {$node['key']}: {\n" . implode("\n", $children) . "\n  $indent}";
                break;
            case 'unchanged':
                $acc[] = "$indent  {$node['key']}: " . stringify($node['value'], $depth);
                break;
            case 'added':
                $acc[] = "$indent+ {$node['key']}: " . stringify($node['value'], $depth);
                break;
            case 'deleted':
                $acc[] = "$indent- {$node['key']}: " . stringify($node['value'], $depth);
                break;
            case 'changed':
                $acc[] = "$indent- {$node['key']}: " . stringify($node['beforeValue'], $depth);
                $acc[] = "$indent+ {$node['key']}: " . stringify($node['afterValue'], $depth);
                break;
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
        return $acc;
    });
}

function stringify($value, int $depth): string
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    if (is_array($value)) {
        return stringifyNode($value, $depth + 1);
    }
    return $value;
}

function stringifyNode(array $node, int $depth): string
{
    $indent = '  ' . str_repeat('    ', $depth);
    $endIndent = '  ' . str_repeat('    ', $depth - 1);
    $uniqueKeys = array_keys($node);

    $strings = array_map(function ($key) use ($node, $indent, $depth) {
        $value = stringify($node[$key], $depth);
        return "$indent  {$key}: $value";
    }, $uniqueKeys);

    $formattedStrings = implode("\n", $strings);
    return "{\n$formattedStrings\n  $endIndent}";
}
