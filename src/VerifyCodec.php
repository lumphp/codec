<?php
namespace Lum\Codec;

interface VerifyCodec
{
    public function encode(string $data, array $params = []) : string;

    public function verify(string $data, array $params = []) : bool;
}