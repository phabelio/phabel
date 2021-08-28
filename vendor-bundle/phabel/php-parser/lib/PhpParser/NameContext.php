<?php

namespace Phabel\PhpParser;

use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\Name\FullyQualified;
use Phabel\PhpParser\Node\Stmt;
class NameContext
{
    /** @var null|Name Current namespace */
    protected $namespace;
    /** @var Name[][] Map of format [aliasType => [aliasName => originalName]] */
    protected $aliases = [];
    /** @var Name[][] Same as $aliases but preserving original case */
    protected $origAliases = [];
    /** @var ErrorHandler Error handler */
    protected $errorHandler;
    /**
     * Create a name context.
     *
     * @param ErrorHandler $errorHandler Error handling used to report errors
     */
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }
    /**
     * Start a new namespace.
     *
     * This also resets the alias table.
     *
     * @param Name|null $namespace Null is the global namespace
     */
    public function startNamespace(Name $namespace = null)
    {
        $this->namespace = $namespace;
        $this->origAliases = $this->aliases = [Stmt\Use_::TYPE_NORMAL => [], Stmt\Use_::TYPE_FUNCTION => [], Stmt\Use_::TYPE_CONSTANT => []];
    }
    /**
     * Add an alias / import.
     *
     * @param Name   $name        Original name
     * @param string $aliasName   Aliased name
     * @param int    $type        One of Stmt\Use_::TYPE_*
     * @param array  $errorAttrs Attributes to use to report an error
     */
    public function addAlias(Name $name, $aliasName, $type, array $errorAttrs = [])
    {
        if (!\is_string($aliasName)) {
            if (!(\is_string($aliasName) || \is_object($aliasName) && \method_exists($aliasName, '__toString') || (\is_bool($aliasName) || \is_numeric($aliasName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($aliasName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($aliasName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $aliasName = (string) $aliasName;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        // Constant names are case sensitive, everything else case insensitive
        if ($type === Stmt\Use_::TYPE_CONSTANT) {
            $aliasLookupName = $aliasName;
        } else {
            $aliasLookupName = \strtolower($aliasName);
        }
        if (isset($this->aliases[$type][$aliasLookupName])) {
            $typeStringMap = [Stmt\Use_::TYPE_NORMAL => '', Stmt\Use_::TYPE_FUNCTION => 'function ', Stmt\Use_::TYPE_CONSTANT => 'const '];
            $this->errorHandler->handleError(new Error(\sprintf('Cannot use %s%s as %s because the name is already in use', $typeStringMap[$type], $name, $aliasName), $errorAttrs));
            return;
        }
        $this->aliases[$type][$aliasLookupName] = $name;
        $this->origAliases[$type][$aliasName] = $name;
    }
    /**
     * Get current namespace.
     *
     * @return null|Name Namespace (or null if global namespace)
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * Get resolved name.
     *
     * @param Name $name Name to resolve
     * @param int  $type One of Stmt\Use_::TYPE_{FUNCTION|CONSTANT}
     *
     * @return null|Name Resolved name, or null if static resolution is not possible
     */
    public function getResolvedName(Name $name, $type)
    {
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        // don't resolve special class names
        if ($type === Stmt\Use_::TYPE_NORMAL && $name->isSpecialClassName()) {
            if (!$name->isUnqualified()) {
                $this->errorHandler->handleError(new Error(\sprintf("'\\%s' is an invalid class name", $name->toString()), $name->getAttributes()));
            }
            return $name;
        }
        // fully qualified names are already resolved
        if ($name->isFullyQualified()) {
            return $name;
        }
        // Try to resolve aliases
        if (null !== ($resolvedName = $this->resolveAlias($name, $type))) {
            return $resolvedName;
        }
        if ($type !== Stmt\Use_::TYPE_NORMAL && $name->isUnqualified()) {
            if (null === $this->namespace) {
                // outside of a namespace unaliased unqualified is same as fully qualified
                return new FullyQualified($name, $name->getAttributes());
            }
            // Cannot resolve statically
            return null;
        }
        // if no alias exists prepend current namespace
        return FullyQualified::concat($this->namespace, $name, $name->getAttributes());
    }
    /**
     * Get resolved class name.
     *
     * @param Name $name Class ame to resolve
     *
     * @return Name Resolved name
     */
    public function getResolvedClassName(Name $name)
    {
        $phabelReturn = $this->getResolvedName($name, Stmt\Use_::TYPE_NORMAL);
        if (!$phabelReturn instanceof Name) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Name, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get possible ways of writing a fully qualified name (e.g., by making use of aliases).
     *
     * @param string $name Fully-qualified name (without leading namespace separator)
     * @param int    $type One of Stmt\Use_::TYPE_*
     *
     * @return Name[] Possible representations of the name
     */
    public function getPossibleNames($name, $type)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $lcName = \strtolower($name);
        if ($type === Stmt\Use_::TYPE_NORMAL) {
            // self, parent and static must always be unqualified
            if ($lcName === "self" || $lcName === "parent" || $lcName === "static") {
                $phabelReturn = [new Name($name)];
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
        }
        // Collect possible ways to write this name, starting with the fully-qualified name
        $possibleNames = [new FullyQualified($name)];
        if (null !== ($nsRelativeName = $this->getNamespaceRelativeName($name, $lcName, $type))) {
            // Make sure there is no alias that makes the normally namespace-relative name
            // into something else
            if (null === $this->resolveAlias($nsRelativeName, $type)) {
                $possibleNames[] = $nsRelativeName;
            }
        }
        // Check for relevant namespace use statements
        foreach ($this->origAliases[Stmt\Use_::TYPE_NORMAL] as $alias => $orig) {
            $lcOrig = $orig->toLowerString();
            if (0 === \strpos($lcName, $lcOrig . '\\')) {
                $possibleNames[] = new Name($alias . \substr($name, \strlen($lcOrig)));
            }
        }
        // Check for relevant type-specific use statements
        foreach ($this->origAliases[$type] as $alias => $orig) {
            if ($type === Stmt\Use_::TYPE_CONSTANT) {
                // Constants are are complicated-sensitive
                $normalizedOrig = $this->normalizeConstName($orig->toString());
                if ($normalizedOrig === $this->normalizeConstName($name)) {
                    $possibleNames[] = new Name($alias);
                }
            } else {
                // Everything else is case-insensitive
                if ($orig->toLowerString() === $lcName) {
                    $possibleNames[] = new Name($alias);
                }
            }
        }
        $phabelReturn = $possibleNames;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get shortest representation of this fully-qualified name.
     *
     * @param string $name Fully-qualified name (without leading namespace separator)
     * @param int    $type One of Stmt\Use_::TYPE_*
     *
     * @return Name Shortest representation
     */
    public function getShortName($name, $type)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        $possibleNames = $this->getPossibleNames($name, $type);
        // Find shortest name
        $shortestName = null;
        $shortestLength = \INF;
        foreach ($possibleNames as $possibleName) {
            $length = \strlen($possibleName->toCodeString());
            if ($length < $shortestLength) {
                $shortestName = $possibleName;
                $shortestLength = $length;
            }
        }
        $phabelReturn = $shortestName;
        if (!$phabelReturn instanceof Name) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Name, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function resolveAlias(Name $name, $type)
    {
        $firstPart = $name->getFirst();
        if ($name->isQualified()) {
            // resolve aliases for qualified names, always against class alias table
            $checkName = \strtolower($firstPart);
            if (isset($this->aliases[Stmt\Use_::TYPE_NORMAL][$checkName])) {
                $alias = $this->aliases[Stmt\Use_::TYPE_NORMAL][$checkName];
                return FullyQualified::concat($alias, $name->slice(1), $name->getAttributes());
            }
        } elseif ($name->isUnqualified()) {
            // constant aliases are case-sensitive, function aliases case-insensitive
            $checkName = $type === Stmt\Use_::TYPE_CONSTANT ? $firstPart : \strtolower($firstPart);
            if (isset($this->aliases[$type][$checkName])) {
                // resolve unqualified aliases
                return new FullyQualified($this->aliases[$type][$checkName], $name->getAttributes());
            }
        }
        // No applicable aliases
        return null;
    }
    private function getNamespaceRelativeName($name, $lcName, $type)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        if (!\is_string($lcName)) {
            if (!(\is_string($lcName) || \is_object($lcName) && \method_exists($lcName, '__toString') || (\is_bool($lcName) || \is_numeric($lcName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($lcName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lcName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $lcName = (string) $lcName;
            }
        }
        if (!\is_int($type)) {
            if (!(\is_bool($type) || \is_numeric($type))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (int) $type;
            }
        }
        if (null === $this->namespace) {
            return new Name($name);
        }
        if ($type === Stmt\Use_::TYPE_CONSTANT) {
            // The constants true/false/null always resolve to the global symbols, even inside a
            // namespace, so they may be used without qualification
            if ($lcName === "true" || $lcName === "false" || $lcName === "null") {
                return new Name($name);
            }
        }
        $namespacePrefix = \strtolower($this->namespace . '\\');
        if (0 === \strpos($lcName, $namespacePrefix)) {
            return new Name(\substr($name, \strlen($namespacePrefix)));
        }
        return null;
    }
    private function normalizeConstName($name)
    {
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $name = (string) $name;
            }
        }
        $nsSep = \strrpos($name, '\\');
        if (\false === $nsSep) {
            return $name;
        }
        // Constants have case-insensitive namespace and case-sensitive short-name
        $ns = \substr($name, 0, $nsSep);
        $shortName = \substr($name, $nsSep + 1);
        return \strtolower($ns) . '\\' . $shortName;
    }
}
