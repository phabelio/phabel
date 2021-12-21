<?php

declare (strict_types=1);
namespace PhabelVendor\PHPStan\PhpDocParser\Ast\PhpDoc;

use PhabelVendor\PHPStan\PhpDocParser\Ast\NodeAttributes;
use PhabelVendor\PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
class TypeAliasImportTagValueNode implements PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string */
    public $importedAlias;
    /** @var IdentifierTypeNode */
    public $importedFrom;
    /** @var string|null */
    public $importedAs;
    /**
     *
     */
    public function __construct(string $importedAlias, IdentifierTypeNode $importedFrom, ?string $importedAs)
    {
        $this->importedAlias = $importedAlias;
        $this->importedFrom = $importedFrom;
        $this->importedAs = $importedAs;
    }
    /**
     *
     */
    public function __toString() : string
    {
        return \trim("{$this->importedAlias} from {$this->importedFrom}" . ($this->importedAs !== null ? " as {$this->importedAs}" : ''));
    }
}
