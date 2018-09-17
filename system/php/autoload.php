<?php

spl_autoload_register(function($className) {
    require_once AMBUE_DIR . DIRECTORY_SEPARATOR . $className . '.php';
});