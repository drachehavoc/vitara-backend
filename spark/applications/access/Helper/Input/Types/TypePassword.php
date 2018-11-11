<?php

namespace Helper\Input\Types;

class TypePassword extends Type
{
    function mount()
    {
        try {
            $salt = \Core\Config::getInstance()->get('access', 'salt');
        } catch (\Exception $e) {
            throw new \Exception('você precisa definir salt no arquivo de configuração access.conf.php');
        }
        $this->value = sha1($this->value . $salt);
    }
}