<?php

namespace Phabel;

class UnresolvedNameException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Cannot obtain FQDN from unresolved name!');
    }
}
