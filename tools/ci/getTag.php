<?php

$tag = \preg_replace('/\\.\\d+$/', '', $argv[1]);
$tag = \explode('.', $tag);
$tag[\count($tag) - 1]++;
$tag = \implode('.', $tag);
echo $tag . '.' . \str_replace('.', '', $argv[2]);
