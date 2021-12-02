<?php
namespace Lum\Codec;

use InvalidArgumentException;

/**
 * signature or verify by OpenSSL
 */
class OpenSSL implements VerifyCodec
{
    /**
     * @param string $data
     * @param array $params
     *       key => 'private key string',
     *       keyType=>'the openssl key type',
     *       passphrase => 'private key passphrase'
     *       signatureAlgorithm => 'algorithm name'
     *
     * @return NULL|string
     * @throws CodecException
     */
    public function encode(string $data, array $params = []) : string
    {
        $passphrase = $params['passphrase'] ?? '';
        $keyType = $params['keyType'];
        $privateKeyId = $this->getPrivateKey($params['key'], $passphrase, $keyType);
        $signatureAlgorithm = $params['signatureAlgorithm'];
        $signature = null;
        if (openssl_sign($data, $signature, $privateKeyId, $signatureAlgorithm)) {
            return $signature;
        }
        $errMsg = openssl_error_string();
        if (!$errMsg) {
            $errMsg = 'sign failed!';
        }
        throw new CodecException(ErrCode::OPENSSL_ENCODE_FAILED, $errMsg);
    }

    /**
     * @param string $data
     * @param array $params
     *         certificate => '',
     *         keyType=>'the openssl key type',
     *         signatureAlgorithm => '',
     *         signature=>''
     *
     * @return bool
     * @throws CodecException
     */
    public function verify(string $data, array $params = []) : bool
    {
        $certificate = $params['certificate'];
        $keyType = $params['keyType'];
        $publicKeyId = $this->getPublicKey($certificate, $keyType);
        $signatureAlgorithm = $params['signatureAlgorithm'];
        $signature = $params['signature'];
        $success = openssl_verify($data, $signature, $publicKeyId, $signatureAlgorithm);
        if (1 === $success) {
            return true;
        }
        $errMsg = openssl_error_string();
        if (!$errMsg) {
            $errMsg = 'ecdsa verify failed!';
        }
        throw new CodecException(ErrCode::OPENSSL_VERIFY_FAILED, $errMsg);
    }

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

