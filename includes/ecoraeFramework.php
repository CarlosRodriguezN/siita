<?php

// Set flag that this is a parent file.
define('_JEXEC', 1);
if (!defined('JPATH_BASE')) {
    define('DS', DIRECTORY_SEPARATOR);
}
//$dirc = explode('/', $_SERVER['PHP_SELF']);
//$dirc = $dirc[1] ;
//define('DIRECTORY_SEPARATOR','/');
//define('DS', '/');
//}
if (!defined('_JDEFINES')) {
    if (!defined('JPATH_BASE')) {
        define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
    }
    //print_r( $_SERVER);exit();
    
    //require_once JPATH_BASE  . 'includes' . DS . 'defines.php';
    require_once JPATH_BASE  . DS.'includes' . DS . 'defines.php';
}

require_once JPATH_BASE  . DS.'includes' . DS . 'framework.php';

// Mark afterLoad in the profiler.
//JDEBUG ? $_PROFILER->mark('afterLoad') : null;
// Instantiate the application.
$app = JFactory::getApplication('site');

// Initialise the application.
$app->initialise();

jimport('joomla.application.component.model');
?>
