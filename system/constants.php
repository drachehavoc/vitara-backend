<?php 

namespace SYSTEM;
const HOME    = __DIR__.DS; 
const HELPERS = HOME.'Helper'.DS;

namespace APPLICATIONS;
const HOME = HOME.'applications'.DS;
require_once HOME.'configuration.php';

namespace APPLICATION;
const HOME = \APPLICATIONS\HOME.\APPLICATIONS\GATES[ HOST ].DS;
require_once HOME.'configuration.php';