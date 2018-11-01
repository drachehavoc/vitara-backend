<?php

namespace Helper\Input\Types;

class TypeAny extends Type
{
    function mount()
    {
        $this->value .= '... mounted';
    }
}