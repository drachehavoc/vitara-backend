<?php return function ($namespace) {
    return new class($namespace) {
        private $namespace;
        
        function __construct($namespace)
        {
            $this->namespace = $namespace;
        }  

        function starsWith ($needle)  
        {
            $needle = "{$needle}\\";
            $found = strpos($this->namespace, $needle) === 0;
            return $found 
                ? ltrim($this->namespace, $needle)
                : null;
        }
    
        function firstFound ($filename, ... $folders)
        {
            foreach ($folders as $folder)
                if (file_exists($file = $folder . DIRECTORY_SEPARATOR . $filename))
                    return $file;
            return null;
        }

        function requireOnce($starts, ... $folders)
        {
            $starts = $this->starsWith($starts);

            if (!$starts) return false;

            $file = $this->firstFound("{$starts}.php", ... $folders);

            if (!$file) return false;

            require_once $file;

            return true;
        }
    };
};