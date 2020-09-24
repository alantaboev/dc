<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider addData
     * @param $before
     * @param $after
     * @param $expect
     * @param $format
     * @throws \Exception
     */
    public function testDiff($before, $after, $expect, $format)
    {
        $this->assertEquals($expect, genDiff($before, $after, $format));
    }

    public function addData()
    {
        $path = __DIR__ . "/fixtures/";
        $beforeJson = $path . "beforeNested.json";
        $afterJson = $path . "afterNested.json";
        $beforeYaml = $path . "beforeNested.yaml";
        $afterYaml = $path . "afterNested.yaml";
        $expectPretty = str_replace("\r\n", "\n", file_get_contents($path . "expectPretty"));
        $expectPlain = str_replace("\r\n", "\n", file_get_contents($path . "expectPlain"));
        $expectJson = str_replace("\r\n", "\n", file_get_contents($path . "expect.json"));
        return [
            'json in pretty' => [$beforeJson, $afterJson, $expectPretty, 'pretty'],
            'yaml in pretty' => [$beforeYaml, $afterYaml, $expectPretty, 'pretty'],
            'json in plain' => [$beforeJson, $afterJson, $expectPlain, 'plain'],
            'yaml in plain' => [$beforeYaml, $afterYaml, $expectPlain, 'plain'],
            'json in json' => [$beforeJson, $afterJson, $expectJson, 'json'],
            'yaml in json' => [$beforeYaml, $afterYaml, $expectJson, 'json']
        ];
    }
}
