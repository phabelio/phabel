<?php

namespace Phabel\Target\Php80\ConstantReplacer;

use Phabel\ClassStorage;
use Phabel\ClassStorageProvider;
use Phabel\Tools;
use PhabelVendor\PhpParser\Node\Expr\ClassConstFetch;
use PhabelVendor\PhpParser\Node\Param;
use PhabelVendor\PhpParser\Node\Stmt\ClassConst;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ConstantReplacer extends ClassStorageProvider
{
    /**
     *
     */
    public static function processClassGraph(ClassStorage $storage, int $iteration, int $innerIteration) : bool
    {
        return $iteration === 1 && $innerIteration === 1;
    }
    /**
     *
     */
    private bool $inParam = \false;
    /**
     *
     */
    public function enterParam(Param $param)
    {
        $this->inParam = \true;
    }
    /**
     *
     */
    public function leaveParam(Param $param)
    {
        if ($param->default) {
            try {
                $param->default = Tools::fromLiteral(Tools::toLiteral($param->default));
            } catch (\Throwable $phabel_db614eae2f1dc6dc) {
                // Ignore errors caused by constant lookups
            }
        }
        $this->inParam = \false;
    }
    /**
     *
     */
    public function enterFetch(ClassConstFetch $fetch)
    {
        if ($this->inParam) {
            try {
                return self::fromLiteral(((string) $fetch->class === 'self' ? $this->storage : $this->getGlobalClassStorage()->getClassByName(self::getFqdn($fetch->class)))->getConstant($fetch->name));
            } catch (\Throwable $e) {
                // Ignore missing constants for now since we didn't implement normal constant lookup
            }
        }
    }
    /**
     *
     */
    public function enterConstant(ClassConst $constants)
    {
        foreach ($constants->consts as $const) {
            try {
                $const->value = self::fromLiteral($this->storage->getConstant($const->name));
            } catch (\Throwable $phabel_4d5cde7d65fdba1e) {
                // Ignore missing constants for now since we didn't implement normal constant lookup
            }
        }
    }
}
