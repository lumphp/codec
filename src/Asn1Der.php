<?php
namespace Lum\Codec;

final class Asn1Der implements Codec
{
    const ASN1_INTEGER = 0x02;
    const ASN1_SEQUENCE = 0x10;
    const ASN1_BIT_STRING = 0x03;
    const NULL_CHAR = "\x00";

    /**
     * decode a DER object.
     *
     * @param string $data binary signature in DER format
     * @param array $params
     *           'keySize'=> '',the number of bits in the key
     *
     * @return  string  the signature
     */
    public function decode(string $data, array $params = []) : string
    {
        $keySize = $params['keySize'];
        [$offset, $_] = self::readDER($data);
        [$offset, $r] = self::readDER($data, $offset);
        [$offset, $s] = self::readDER($data, $offset);
        $r = ltrim($r, self::NULL_CHAR);
        $s = ltrim($s, self::NULL_CHAR);
        $r = str_pad($r, $keySize / 8, self::NULL_CHAR, STR_PAD_LEFT);
        $s = str_pad($s, $keySize / 8, self::NULL_CHAR, STR_PAD_LEFT);

        return $r.$s;
    }

    /**
     * Convert an signature to an ASN.1 DER sequence
     *
     * @param string $data The signature to convert
     * @param array $params
     *
     * @return  string The encoded DER object
     */
    public function encode(string $data, array $params = []) : string
    {
        [$r, $s] = str_split($data, intval(strlen($data) / 2));
        $r = ltrim($r, self::NULL_CHAR);
        $s = ltrim($s, self::NULL_CHAR);
        if (ord($r[0]) > 0x7f) {
            $r = self::NULL_CHAR.$r;
        }
        if (ord($s[0]) > 0x7f) {
            $s = self::NULL_CHAR.$s;
        }

        return self::encodeDER(
            self::ASN1_SEQUENCE,
            self::encodeDER(self::ASN1_INTEGER, $r).self::encodeDER(self::ASN1_INTEGER, $s)
        );
    }

    /**
     * Encodes a value into a DER object.
     *
     * @param int $type DER tag
     * @param string $value the value to encode
     *
     * @return  string  the encoded object
     */
    private static function encodeDER(int $type, string $value) : string
    {
        $tag_header = 0;
        if ($type === self::ASN1_SEQUENCE) {
            $tag_header |= 0x20;
        }
        // Type
        $der = chr($tag_header | $type);
        // Length
        $der .= chr(strlen($value));

        return $der.$value;
    }

    /**
     * Reads binary DER-encoded data and decodes into a single object
     *
     * @param string $der the binary data in DER format
     * @param int $offset the offset of the data stream containing the object
     * to decode
     *
     * @return array [$offset, $data] the new offset and the decoded object
     */
    private static function readDER(string $der, int $offset = 0) : array
    {
        $pos = $offset;
        $size = strlen($der);
        $constructed = (ord($der[$pos]) >> 5) & 0x01;
        $type = ord($der[$pos++]) & 0x1f;
        // Length
        $len = ord($der[$pos++]);
        if ($len & 0x80) {
            $n = $len & 0x1f;
            $len = 0;
            while ($n-- && $pos < $size) {
                $len = ($len << 8) | ord($der[$pos++]);
            }
        }
        // Value
        if ($type == self::ASN1_BIT_STRING) {
            $pos++; // Skip the first contents octet (padding indicator)
            $data = substr($der, $pos, $len - 1);
            $pos += $len - 1;
        } elseif (!$constructed) {
            $data = substr($der, $pos, $len);
            $pos += $len;
        } else {
            $data = null;
        }

        return [$pos, $data];
    }
}