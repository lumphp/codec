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
    const ECDSA_ENCODE_FAILED = 105;
    const ECDSA_VERIFY_FAILED = 106;
    const EDDSA_ENCODE_FAILED = 107;
    const EDDSA_VERIFY_FAILED = 108;
    const RSA_ENCODE_FAILED = 109;
    const RSA_VERIFY_FAILED = 110;
    const HASH_ENCODE_FAILED = 111;
    const HASH_VERIFY_FAILED = 112;
}