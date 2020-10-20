<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );


/**
 * 
 *  Clase que Retorna en formato JSON, Provincias, Cantones y Parroquias 
 *  pertenecientes a una determinada Unidad Territorial
 */
class ReportesViewProyectos extends JView
{

    protected $_tpoReporte;
    protected $_items;
    protected $_indicadores;
    protected $_dtaIndicador;
    
            
    function display( $tpl = null )
    {
        $this->_tpoReporte  = JRequest::getVar( 'tpo' );
        $this->_items       = $this->_getDataEntidad( JRequest::getVar( 'tpoEntidad' ), JRequest::getVar( 'id' ) );
        $dtaView            = $this->loadTemplate();
        
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/pdf' );

        //  Instancio la clase DOMPDF, la cual gestiona la creacion de un archivo PDF
        $dompdf = new DOMPDF();
        $dompdf->set_paper( 'A4', 'portrait' );

        $dompdf->load_html( $dtaView );
        $dompdf->render();
        $dompdf->stream( $this->_items->nombreProyecto. '.pdf' );

        exit;
    }

    private function _getDataEntidad( $tpoEntidad, $id )
    {
        $rst = false;

        switch ( $tpoEntidad ){
            case 'pry':
                $mdProyecto = $this->getModel();
                $rst = $mdProyecto->getDtaProyecto( $id );
            break;
        }
        
        return $rst;
    }

}
