<?php
namespace Lum\Codec;

/**
 * class ErrCode
 */
final class ErrCode
{
    const ENCODE_FAILED = 101;
    const DECODE_FAILED = 102;
    const JSON_ENCODE_FAILED = 103;
    const JSON_DECODE_FAILED = 104;
    const OPENSSL_ENCODE_FAILED = 105;
    const OPENSSL_VERIFY_FAILED = 106;
    const SODIUM_ENCODE_FAILED = 107;
    const SODIUM_VERIFY_FAILED = 108;
    const HASH_ENCODE_FAILED = 109;
    const HASH_VERIFY_FAILED = 110;
}