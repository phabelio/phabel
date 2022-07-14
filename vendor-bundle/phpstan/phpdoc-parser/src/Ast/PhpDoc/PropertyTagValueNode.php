<?php

declare (strict_types=1);
namespace PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc;

use PhabelVendor\PHPStan\PhpDocParser\Ast\NodeAttributes;
use PhabelVendor\PHPStan\PhpDocParser\Ast\Type\TypeNode;
use function trim;
class PropertyTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var TypeNode */
    public $type;
    /** @var string */
    public $propertyName;
    /** @var string (may be empty) */
    public $description;
    public function __construct(TypeNode $type, string $propertyName, string $description)
    {
        $this->type = $type;
        $this->propertyName = $propertyName;
        $this->description = $description;
    }
    public function __toString() : string
    {
        return trim("{$this->type} {$this->propertyName} {$this->description}");
    }
}
