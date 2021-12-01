<?php
namespace Lum\Codec;

/**
 * class ECDsa
 */
final class ECDsa extends BaseOpenSSL implements VerifyCodec
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
        $signatureAlgorithm = $params['signatureAlgorithm'] ?? OPENSSL_ALGO_SHA256;
        $signature = null;
        if (openssl_sign($data, $signature, $privateKeyId, $signatureAlgorithm)) {
            return $signature;
        }
        $errMsg = openssl_error_string();
        if (!$errMsg) {
            $errMsg = 'sign failed!';
        }
        throw new CodecException(ErrCode::ECDSA_ENCODE_FAILED, $errMsg);
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
        $signatureAlgorithm = $params['signatureAlgorithm'] ?? OPENSSL_ALGO_SHA256;
        $signature = $params['signature'];
        $success = openssl_verify($data, $signature, $publicKeyId, $signatureAlgorithm);
        if (1 === $success) {
            return true;
        }
        $errMsg = openssl_error_string();
        if (!$errMsg) {
            $errMsg = 'ecdsa verify failed!';
        }
        throw new CodecException(ErrCode::ECDSA_VERIFY_FAILED, $errMsg);
    }
}

