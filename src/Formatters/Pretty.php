<?php

namespace Differ\Formatters\Pretty;

function render(array $differences): string
{
    $lines = getLines($differences); // Получение строк
    $implodeLines = implode("\n", $lines);
    return "{\n$implodeLines\n}\n"; // Возврат всех строк в фигурных скобках
}

function getLines(array $diffTree, $depth = 0): array
{
    $indent = '  ' . str_repeat('    ', $depth); // Отступ
    return array_reduce($diffTree, function ($acc, $node) use ($indent, $depth) {
        switch ($node['type']) {
            case 'parent':
                $children = getLines($node['children'], $depth + 1);
                $acc[] = "$indent  {$node['name']}: {\n" . implode("\n", $children) . "\n  $indent}";
                break;
            case 'unchanged':
                $acc[] = "$indent  {$node['key']}: " . getValue($node['value'], $depth);
                break;
            case 'added':
                $acc[] = "$indent+ {$node['key']}: " . getValue($node['value'], $depth);
                break;
            case 'deleted':
                $acc[] = "$indent- {$node['key']}: " . getValue($node['value'], $depth);
                break;
            case 'changed':
                $acc[] = "$indent- {$node['key']}: " . getValue($node['beforeValue'], $depth);
                $acc[] = "$indent+ {$node['key']}: " . getValue($node['afterValue'], $depth);
                break;
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
        return $acc;
    });
}

// Проверка значения ключа. Если булев, то возврат строки, соответствующей значению
function getValue($value, int $depth)
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    if (is_array($value)) {
        return convertNode($value, $depth + 1);
    }
    return $value;
}

// Преобразование вложенного узла
function convertNode(array $node, int $depth): string
{
    $indent = '  ' . str_repeat('    ', $depth);
    $endIndent = '  ' . str_repeat('    ', $depth - 1);
    $uniqueKeys = array_keys($node);
    $strings = array_reduce($uniqueKeys, function ($acc, $key) use ($node, $indent, $depth) {
        $value = getValue($node[$key], $depth);
        $acc[] = "$indent  {$key}: $value";
        return $acc;
    });
    $formattedStrings = implode("\n", $strings);

    return "{\n$formattedStrings\n  $endIndent}";
}
