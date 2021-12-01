<?php
namespace Lum\Codec;

/**
 *  sodium abstract class
 */
class BaseSodium
{
    final protected function getPrivateKey(string $keyPair) : string
    {
        return base64_encode(sodium_crypto_sign_secretkey($keyPair));
    }

    final protected function getPublicKey(string $keyPair) : string
    {
        return base64_encode(sodium_crypto_sign_publickey($keyPair));
    }
}
