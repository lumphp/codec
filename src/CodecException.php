<?php
namespace Lum\Codec;

use Exception;
use Throwable;

/**
 * Codec exception
 */
class CodecException extends Exception
{
    private $data;

    /**
     * @param int $code
     * @param string $message
     * @param null $data
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, $message = "", $data = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }
}