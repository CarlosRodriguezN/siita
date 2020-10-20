
<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'alineacion' . DS . 'estructura.php';

class Estructura {

    public function __construct() {
        
    }

    public function getEstructura($idAgenda) {
        $result = FALSE;
        $db = JFactory::getDBO();
        $tbEstructura = new JTableEstructura($db);
        $result = $tbEstructura->getEstructura($idAgenda);

        return $result;
    }

}

?>
