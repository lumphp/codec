<?php
namespace Lum\Codec;

interface Codec
{
    public function encode(string $data, array $params = []) : string;

    public function decode(string $data, array $params = []);
}