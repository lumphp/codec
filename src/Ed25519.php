<?php
namespace Lum\Codec;

use Exception;

final class Ed25519 extends BaseSodium implements VerifyCodec
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
            $key = $params['key'];
            $privateKey = $this->getPrivateKey($key);
            $lines = array_filter(explode("\n", $privateKey));
            $secretKey = base64_decode(end($lines));

            return sodium_crypto_sign_detached($data, $secretKey);
        } catch (Exception $e) {
            throw new CodecException($e->getCode(), $e->getMessage());
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
            $key = $params['key'];
            $message = $params['message'];
            $content = $this->getPublicKey($key);
            $lines = array_filter(explode("\n", $content));
            $publicKey = base64_decode(end($lines));

            return sodium_crypto_sign_verify_detached($data, $message, $publicKey);
        } catch (Exception $e) {
            throw new CodecException($e->getCode(), $e->getMessage());
        }
    }
}
