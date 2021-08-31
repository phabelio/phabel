<?php

declare (strict_types=1);
namespace Phabel\PhpParser\Node\Expr\Cast;

use Phabel\PhpParser\Node\Expr\Cast;
class Bool_ extends Cast
{
    public function getType() : string
    {
        return 'Expr_Cast_Bool';
    }
}
