<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');


//  Adjunto libreria para la creacion de archivos Excel
jimport( 'excel.PHPExcel' );

//  Adjunto libreria para la creacion de archivos Excel
jimport( 'word.PHPWord' );

//  Adjunto libreria para creacion de archivos PDF
require_once( JPATH_LIBRARIES.DS.'dompdf'.DS.'dompdf_config.inc.php' );


/**
 * 
 *  Controlador Fases
 * 
 */
class ProyectosControllerProyecto extends JControllerForm
{
    protected $view_list = 'proyectos';
    
    protected function allowAdd()
    {
        return true;
    }
    
    protected function allowEdit()
    {
        return true;
    }
    
    function add()
    {
        parent::add();
    }
    
    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=proyectos', false ) );
    }

    /**
     * 
     * Gestiono el registro de imagenes
     * 
     * @return boolean
     */
    public function registroImagenes()
    {
        $ban = true;
        
        //  Verifico la existencia de imagenes en el formulario
        if( isset( $_FILES ) ){
            //  Accedo al modelo del componente com_programa
            $modelo = $this->getModel();
            return $modelo->saveImagesProyecto();
        }

        return $ban;
    }

    /**
     * 
     * Asigno a un proyecto la propuesta de un proyecto
     * 
     * @return type
     */
    public function addCanastaProyecto()
    {
        $modelo = $this->getModel();
        $lstPropuestas = JRequest::getVar( 'cid' );
        $idProyecto = JRequest::getVar( 'cbLstProyectos' );
        return $modelo->asignarPropuestasProyecto( $idProyecto, $lstPropuestas );
    }
    
    
    /**
     * 
     * Creo un NUEVO proyectos a partir de un lista de propuestas de proyectos
     * 
     * @return type
     */
    public function crearProyecto()
    {
        $modelo = $this->getModel();
        $lstPropuestas = JRequest::getVar( 'cid' );
        
        return $modelo->crearProyectoConPropuestas( $lstPropuestas );
    }
    
    
    /**
     *  
     *  Retono un archivo en formato PDF, con informacion General y detallada 
     *  de un determinado proyecto
     * 
     */
    public function senplades()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $url = 'index.php?option=com_reportes&view=proyectos&format=pdf&tmpl=component&tpo=senplades&tpoEntidad=pry&id='. $idProyecto;
        $this->setRedirect( JRoute::_( $url, false ) );

        return;
    }
    
    public function ecorae()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        $url = 'index.php?option=com_reportes&view=proyectos&format=pdf&tmpl=component&tpo=ecorae&tpoEntidad=pry&id='. $idProyecto;
        $this->setRedirect( JRoute::_( $url, false ) );

        return;
    }

    /**
     * Retorno al panel de control
     */
    public function panelControl()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=proyectos', false ) );
    }
    
    
    public function guardarContinuar()
    {
        $idProyecto = JRequest::getVar( 'intCodigo_pry' );
        
        $this->setRedirect( JRoute::_( 'index.php?option=com_proyectos&view=proyecto&layout=edit&intCodigo_pry='.$idProyecto, false ) );
    }
    
}