<?php
namespace Lum\Codec;

/**
 * codec util
 */
final class CodecUtil
{
    const BASE64 = 'base64';
    const URL_BASE64 = 'urlBase64';
    const JSON = 'json';
    const JWT = 'jwt';
    const ASN1DER = 'der';
    const ECDSA = 'ECDsa';
    const ED25519 = 'Ed25519';
    const SUPPORTED = [
        self::BASE64 => Base64::class,
        self::URL_BASE64 => UrlBase64::class,
        self::JSON => Json::class,
        self::JWT => Jwt::class,
        self::ASN1DER => Asn1Der::class,
        self::ECDSA => ECDsa::class,
        self::ED25519 => Ed25519::class,
    ];

    /**
     * @param string $type
     * @param mixed $data
     * @param array $params
     *
     * @return mixed
     * @throws CodecException
     */
    public static function encode(string $type, $data, array $params = [])
    {
        $className = self::SUPPORTED[$type] ?? '';
        if (!$className) {
            throw new CodecException(ErrCode::ENCODE_FAILED, sprintf('not found codec parser of %s', $type));
        }
        $instance = new $className;

        return $instance->encode($data, $params);
    }

    /**
     * @param string $type
     * @param string $data
     * @param array $params
     *
     * @return mixed
     * @throws CodecException
     */
    public static function decode(string $type, string $data, array $params = [])
    {
        $className = self::SUPPORTED[$type] ?? '';
        if (!$className) {
            throw new CodecException(ErrCode::DECODE_FAILED, sprintf('not found codec parser of %s', $type));
        }
        $instance = new $className;
        if (!$instance instanceof Codec) {
            throw new CodecException(
                ErrCode::DECODE_FAILED, sprintf('not support decode of %s', $type)
            );
        }

        return $instance->decode($data, $params);
    }

    /**
     * @param string $type
     * @param string $data
     * @param array $params
     *
     * @return mixed
     * @throws CodecException
     */
    public static function verify(string $type, string $data, array $params = [])
    {
        $className = self::SUPPORTED[$type] ?? '';
        if (!$className) {
            throw new CodecException(ErrCode::DECODE_FAILED, sprintf('not found codec parser of %s', $type));
        }
        $instance = new $className;
        if (!$instance instanceof VerifyCodec) {
            throw new CodecException(
                ErrCode::DECODE_FAILED, sprintf('not support verify of %s', $type)
            );
        }

        return $instance->verify($data, $params);
    }
}