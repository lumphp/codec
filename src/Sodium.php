<?php
namespace Lum\Codec;

use Exception;

class Sodium implements VerifyCodec
{
    /**
     * @param string $data
     * @param array $params
     *
     * @return string
     * @throws CodecException
     */
    public function encode(string $data, array $params = []) : string
    {
        try {
            $keyPair = $params['keyPair'];
            $privateKey = $this->getPrivateKey($keyPair);
            $lines = array_filter(explode("\n", $privateKey));
            $secretKey = base64_decode(end($lines));

            return sodium_crypto_sign_detached($data, $secretKey);
        } catch (Exception $e) {
            throw new CodecException(ErrCode::SODIUM_ENCODE_FAILED, $e->getMessage());
        }
    }

    /**
     * @param string $data
     * @param array $params
     *
     * @return bool
     * @throws CodecException
     */
    public function verify(string $data, array $params = []) : bool
    {
        try {
            $keyPair = $params['keyPair'];
            $signature = $params['signature'];
            $content = $this->getPublicKey($keyPair);
            $lines = array_filter(explode("\n", $content));
            $publicKey = base64_decode(end($lines));

            return sodium_crypto_sign_verify_detached($signature, $data, $publicKey);
        } catch (Exception $e) {
            throw new CodecException(ErrCode::SODIUM_VERIFY_FAILED, $e->getMessage());
        }
    }

    final protected function getPrivateKey(string $keyPair) : string
    {
        return base64_encode(sodium_crypto_sign_secretkey($keyPair));
    }

    final protected function getPublicKey(string $keyPair) : string
    {
        return base64_encode(sodium_crypto_sign_publickey($keyPair));
    }
}
