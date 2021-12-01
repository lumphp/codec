<?php
namespace Lum\Codec;

/**
 * Base64 class
 */
final class Base64 implements Codec
{
    /**
     * @param string $data
     * @param array $params
     *
     * @return string
     */
    public function encode(string $data, array $params = []) : string
    {
        return base64_encode($data);
    }

    /**
     * @param string $data
     * @param array $params
     *
     * @return false|string
     */
    public function decode(string $data, array $params = [])
    {
        $strict = $params['strict'] ?? null;

        return base64_decode($data, $strict);
    }
}
