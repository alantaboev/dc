<?php

namespace Differ\Formatters\Pretty;

function render(array $differences)
{
    $lines = getLines($differences); // Получение строк
    return "{\n$lines\n}\n"; // Возврат всех строк в фигурных скобках
}

function getLines(array $tree, $depth = 0)
{
    $indent = '  ' . str_repeat('    ', $depth); // Отступ строки (4 пробела)
    $strings = array_reduce($tree, function ($acc, $node) use ($indent, $depth) {
        switch ($node['type']) {
            case 'node':
                $children = getLines($node['children'], $depth + 1);
                $acc[] = "$indent  {$node['name']}: {\n$children\n  $indent}";
                break;
            case 'unchanged':
                $value = checkValue($node['value'], $depth);
                $acc[] = "$indent  {$node['key']}: $value";
                break;
            case 'added':
                $value = checkValue($node['value'], $depth);
                $acc[] = "$indent+ {$node['key']}: $value";
                break;
            case 'deleted':
                $value = checkValue($node['value'], $depth);
                $acc[] = "$indent- {$node['key']}: $value";
                break;
            case 'changed':
                $oldValue = checkValue($node['beforeValue'], $depth);
                $newValue = checkValue($node['afterValue'], $depth);
                $acc[] = "$indent- {$node['key']}: $oldValue";
                $acc[] = "$indent+ {$node['key']}: $newValue";
                break;
            default:
                throw new \Exception("Unknown action '{$node['type']}'");
        }
        return $acc;
    }, $acc = []);
    return implode("\n", $strings); // Возврат массива в строки с разделителем переноса
}

// Проверка значения ключа. Если булев, то возврат строки, соответствующей значению
function checkValue($value, int $depth)
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
function convertNode(array $node, int $depth)
{
    $indent = '  ' . str_repeat('    ', $depth);
    $endIndent = '  ' . str_repeat('    ', $depth - 1);
    $uniqueKeys = array_keys($node);
    $strings = array_reduce($uniqueKeys, function ($acc, $key) use ($node, $indent, $depth) {
        $value = checkValue($node[$key], $depth);
        $acc[] = "$indent  {$key}: $value";
        return $acc;
    });
    $formattedStrings = implode("\n", $strings);

    return "{\n$formattedStrings\n  $endIndent}";
}
