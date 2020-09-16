<?php

namespace Differ\Json;

function renderJson(array $differences)
{
    return json_encode($differences);
}
