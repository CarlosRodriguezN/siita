
<?php

//  Importa la tabla necesaria para hacer la gestion
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'alineacion' . DS . 'item.php';

class Items {

    public function __construct() {
        
    }

    /**
     * 
     * @param type $idAgenda
     * @param type $idPadre
     * @return type
     */
    public function getItems($idAgenda, $idPadre) {
        $result = FALSE;
        $db = JFactory::getDBO();

        $tbItems = new JTableItem($db);
        $result = $tbItems->getItems($idAgenda, $idPadre);

        return $result;
    }

    /**
     * 
     * @param type $idItem
     * @return type
     */
    public function getItem($idItem) {
        $result = FALSE;
        $db = JFactory::getDBO();

        $tbItems = new JTableItem($db);
        $result = $tbItems->getItemById($idItem);

        return $result;
    }

}

?>
