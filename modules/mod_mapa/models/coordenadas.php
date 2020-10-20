<?php

defined( '_JEXEC' ) or die();
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'coordenadas.php';

class CoordenadasModel extends JModel
{

    function getCoordenadas( $idTgPry )
    {
        $db = JFactory::getDbo();
        $layers = new JTableCoordenadas( $db );
        return $layers->getFigCoordenadas( $idTgPry ); // retorna las coordenas de una figura
    }

}