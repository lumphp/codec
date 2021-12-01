<?php
namespace Lum\Codec;

use InvalidArgumentException;

/**
 * class BaseOpenSSL
 */
 class BaseOpenSSL
{
    /**
     * @param string $key
     * @param string $passphrase
     * @param $keyType
     *
     * @return resource
     */
    final protected function getPrivateKey(string $key, string $passphrase = '', $keyType = null)
    {
        $privateKey = openssl_pkey_get_private($key, $passphrase);
        $this->validateKey($privateKey, $keyType);

        return $privateKey;
    }

    /**
     * @param mixed $pem
     * @param $keyType
     *
     * @return resource
     */
    final protected function getPublicKey($pem, $keyType)
    {
        $publicKey = openssl_pkey_get_public($pem);
        $this->validateKey($publicKey, $keyType);

        return $publicKey;
    }

    /**
     * Raises an exception when the key type is not the expected type
     *
     * @param resource $key
     * @param $keyType
     *
     * @throws InvalidArgumentException
     */
    final protected function validateKey($key, $keyType) : void
    {
        if (!is_resource($key)) {
            throw new InvalidArgumentException(
                sprintf('It was not possible to parse your key, reason: %s', openssl_error_string())
            );
        }
        $details = openssl_pkey_get_details($key);
        if (!isset($details['key']) || $details['type'] !== $keyType) {
            throw new InvalidArgumentException('This key is not compatible!');
        }
    }
}
