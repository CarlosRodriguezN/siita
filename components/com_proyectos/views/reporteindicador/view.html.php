<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

//load the JToolBar library and create a toolbar
jimport('joomla.html.toolbar');

/**
 * Clase que muestra un conjunto de Proyectos
 */
class ProyectosViewReporteIndicador extends JView
{    
    protected $_items;

    /**
    * 
    *   HelloWorlds view display method
    * 
    *   @return void
    * 
    */
    function display( $tpl = null )
    {
        $modProyecto = $this->getModel();

        //  Obtengo Informacion de un Indicadores asociados a una entidad
        $this->_items = $modProyecto->getDtaIndicador( 100 );
        
        //  Carga Indicadores en formato HTML
        $valueToPdf .= $this->loadTemplate();
        
        //  Genera y descarga archivo PDF de los Indicadores de un proyecto
        $this->_generarPdf( $this->_items["nombreIndicador"], $valueToPdf );
    }
    
    
    /**
    * Method to set up the document properties
    *
    * @return void
    */
    protected function setDocument() 
    {
        $document = JFactory::getDocument();
        
        //  Accedemos a la hoja de estilos
        $document->addStyleSheet( 'libraries/dompdf/www/test/css/print_static.css' );
    }
    
    
    
    private function _generarPdf( $nombreArchivo, $dta )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/pdf' );
        
        $dompdf = new DOMPDF();
        $dompdf->set_paper( 'A4', 'portrait' );

        $dompdf->load_html( $dta );
        $dompdf->render();
        $dompdf->stream( $nombreArchivo.'.pdf' );
        
        exit;
    }
}