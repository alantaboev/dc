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
     * @param $referenceFile
     * @param $format
     */
    public function testDiff($before, $after, $referenceFile, $format)
    {
        $expect = file_get_contents($referenceFile);
        $this->assertEquals($expect, genDiff($before, $after, $format));
    }

    public function addData()
    {
        $path = __DIR__ . "/fixtures/";
        $beforeJson = $path . "beforeNested.json";
        $afterJson = $path . "afterNested.json";
        $beforeYaml = $path . "beforeNested.yaml";
        $afterYaml = $path . "afterNested.yaml";
        $expectPretty = $path . "expectPretty";
        $expectPlain = $path . "expectPlain";
        $expectJson = $path . "expect.json";
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
