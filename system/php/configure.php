<?php

//
//
//

date_default_timezone_set(AMBUE_TMEZONE);

//
//
//

ini_set('display_errors', !AMBUE_PRODUCTION); 
ini_set('log_errors', true); 
ini_set('error_log', AMBUE_FILE_LOG_PHP); 
ini_set('log_errors_max_len', 1024); 