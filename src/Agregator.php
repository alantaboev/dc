<?php

namespace Differ\Agregator;

use function Funct\Collection\union;

function agregateDiff(array $before, array $after): array
{
    $keys = union(array_keys($before), array_keys($after));
    sort($keys);
    return array_map(fn($key) => getChanges($key, $before, $after), $keys);
}

function getChanges(string $key, array $before, array $after): array
{
    if (!array_key_exists($key, $before)) {
        return createSimpleStructure($key, $after[$key], 'added');
    }
    if (!array_key_exists($key, $after)) {
        return createSimpleStructure($key, $before[$key], 'deleted');
    }
    if (is_array($before[$key]) && is_array($after[$key])) {
        return createNode($key, $before[$key], $after[$key], 'parent');
    }
    if ($before[$key] !== $after[$key]) {
        return createStructure($key, $before[$key], $after[$key], 'changed');
    }
    return createSimpleStructure($key, $before[$key], 'unchanged');
}

function createSimpleStructure(string $key, $value, string $type): array
{
    return ['key' => $key, 'value' => $value, 'type' => $type];
}

function createStructure(string $key, $oldValue, $newValue, string $type): array
{
    return ['key' => $key, 'beforeValue' => $oldValue, 'afterValue' => $newValue, 'type' => $type];
}

function createNode(string $key, array $before, array $after, string $type): array
{
    return ['key' => $key, 'type' => $type, 'children' => agregateDiff($before, $after)];
}
