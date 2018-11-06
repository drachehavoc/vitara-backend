<?php return function () {
    class ExceptionCustom extends \Exception
    {
        function __construct()
        {
            $this->file = null;
            $this->line = null;
            $this->message = "ISSO É CUSTOM";
            $this->code = ["CODIGO", "LOKO"];
        }
    }

    switch ($this->matches['err']) {
        // E_NOTICE
        case 1:
            $xx + 1;
            break;
            
        // E_WARNING
        case 2:
            100 / 0;
            break;
        
        // EXCEPTION
        case 3:
            throw new Exception('message here');
    
        // EXCEPTION
        case 4:
            throw new ExceptionCustom;

        // FATAL ERROR
        case 5:
            function x(integer $z)
            {
            }
            x("asdas");
            break;
    }

    return $this;
};