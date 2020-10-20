<?php

defined( '_JEXEC' ) or die();
require_once JPATH_BASE . DS . 'modules' . DS . 'mod_mapa' . DS . 'tables' . DS . 'tipFigProject.php';

class tipfigsProjectModel extends JModel
{
    function getFigsProject( $idPry )
    {
        $db = JFactory::getDbo();
        $figs = new JTableTipFigProject( $db );

        // El modelo llama a la tabla para recuperar la lista de figs.
        return $figs->getFigsProy( $idPry );
    }

}
