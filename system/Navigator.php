<?php

namespace system;

use system\util\Path;

class Navigator extends Inject
{
    protected $ambue;
    
    function __construct() 
    {
        Parent::__construct();
        Path::requireFunction($this, $this, $this->ambue->runtime->application->routes)();
    }

    public function regex($destination, $function) 
    {
        global $__ELIPSED;
        preg_match($destination, $this->ambue->request->destination, $matches);
        $this->ambue->request->matches = $matches;
        $this->ambue->request->search = array_merge_recursive($matches, $this->ambue->request->query);
        if(!empty($matches)) {
            try {
                $ret['response'] = \Closure::bind($function, $this->ambue, null)();
            } catch (\Exception $e) {
                $ret['response'] = null;
                $ret['error']['message'] = $e->getMessage();
                $ret['error']['detail'] = $e->getCode();
                $ret['error']['type'] = get_class($e);
                $stackTrace = $e->getTrace();
            }
            
            if (!AMBUE_PRODUCTION) {
                $ret['debug']['error'] = $stackTrace ?? null;
                $ret['debug']['elipsed'] = (microtime(true) - $__ELIPSED) * 1000;
            }

            die( json_encode($ret) );
        }
        return $this;
    }
}