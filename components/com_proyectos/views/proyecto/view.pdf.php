<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * 
 *  Clase que Retorna en formato JSON, Provincias, Cantones y Parroquias 
 *  pertenecientes a una determinada Unidad Territorial
 */
class ProyectosViewProyecto extends JView
{
    function display( $tpl = null )
    { 
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/pdf' );

        //  Instancio la clase DOMPDF, la cual gestiona la creacion de un archivo PDF
        $dompdf = new DOMPDF();
        $dompdf->set_paper( 'A4', 'portrait' );

        $dompdf->load_html( 'carlosLuis' );
        $dompdf->render();
        $dompdf->stream( 'ecorae.pdf' );

        exit;
    }
}