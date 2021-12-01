<?php
namespace Lum\Codec;

/**
 * Json-Web-Token codec class
 */
final class Jwt implements Codec
{
    /**
     * @var UrlBase64 $urlBase64
     */
    private $urlBase64;
    /**
     * @var Json $json
     */
    private $json;

    public function __construct()
    {
        $this->urlBase64 = new UrlBase64;
        $this->json = new Json;
    }

    /**
     * @param mixed $data
     * @param array $params
     *
     * @return string
     * @throws CodecException
     */
    public function encode($data, array $params = []) : string
    {
        //json encode
        $json = $this->json->encode($data, $params);

        //base64 encode
        return $this->urlBase64->encode($json);
    }

    /**
     * @param string $data
     * @param array $params
     *
     * @return mixed
     * @throws CodecException
     */
    public function decode(string $data, array $params = [])
    {
        //base64 decode
        $json = $this->urlBase64->decode($data);

        //base64 encode
        return $this->json->decode($json, $params);
    }
}
