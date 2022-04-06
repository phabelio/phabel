<?php

declare (strict_types=1);
namespace PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc;

use PhabelVendor\PHPStan\PhpDocParser\Ast\NodeAttributes;
use PhabelVendor\PHPStan\PhpDocParser\Parser\ParserException;
class InvalidTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string (may be empty) */
    public $value;
    /** @var ParserException */
    public $exception;
    public function __construct(string $value, ParserException $exception)
    {
        $this->value = $value;
        $this->exception = $exception;
    }
    public function __toString() : string
    {
        return $this->value;
    }
}
