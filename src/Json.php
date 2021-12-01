<?php
namespace Lum\Codec;

/**
 * Json codec class
 */
final class Json implements Codec
{
    /**
     * @param array|object $data
     * @param array $params
     *
     * @return string
     * @throws CodecException
     */
    public function encode($data, array $params = []) : string
    {
        $options = intval($params['options'] ?? 0);
        $depth = intval($params['depth'] ?? 0);
        $depth = $depth ?: 512;
        $json = json_encode($data, $options, $depth);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new CodecException(
                ErrCode::JSON_ENCODE_FAILED, sprintf('json encode failed: %s', json_last_error_msg())
            );
        }

        return $json;
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
        $assoc = $params['assoc'] ?? null;
        $result = json_decode($data, (bool)$assoc);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new CodecException(
                ErrCode::JSON_DECODE_FAILED, sprintf('json decode failed: %s', json_last_error_msg())
            );
        }

        return $result;
    }
}
