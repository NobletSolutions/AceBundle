<?php

namespace NS\AceBundle\Filter\Search;

use JsonSerializable;

interface Select2SearchResultInterface extends JsonSerializable
{
    public function getId();
    public function getText(): string;
}
