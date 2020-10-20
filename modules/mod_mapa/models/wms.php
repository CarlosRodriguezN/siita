<?php

defined('_JEXEC') or die();
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'wms.php';

class WMSModel extends JModel {

    function getWMS() {
        $db = JFactory::getDbo();
        $wms = new JTableWMS($db);
        return json_encode(array_values($wms->getWMS()));
    }
}
