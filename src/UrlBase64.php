<?php
namespace Lum\Codec;

/**
 * UrlBase64 class
 */
final class UrlBase64 implements Codec
{
    /**
     * @var Base64 $codec
     */
    private $base64;

    public function __construct()
    {
        $this->base64 = new Base64;
    }

    /**
     * @param string $data
     * @param array $params
     *
     * @return string
     */
    public function encode(string $data, array $params = []) : string
    {
        return str_replace('=', '', strtr($this->base64->encode($data), '+/', '-_'));
    }

    /**
     * @param string $data
     * @param array $params
     *
     * @return string
     * @throws CodecException
     */
    public function decode(string $data, array $params = []) : string
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return $this->base64->decode(strtr($data, '-_', '+/'));
    }
}
