<?php

//  Importa la tabla necesaria para hacer la gestion

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'objetivosOperativos' . DS . 'tables' . DS . 'objetivoOperativo.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'entidad' . DS . 'entidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'objetivoentidad.php';
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'planinstitucion' . DS . 'planinstitucion.php';
require_once JPATH_BASE . DS . 'components' . DS . 'com_contratos' . DS . 'tables' . DS . 'contrato.php';
require_once JPATH_BASE . DS . 'components' . DS . 'com_programa' . DS . 'tables' . DS . 'programa.php';
require_once JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' . DS . 'proyecto.php';
// Adjunto libreria de gestion de Indicador
jimport( 'ecorae.objetivos.objetivo.objetivo' );
jimport( 'ecorae.entidad.entidad' );

/**
 * Getiona el objetivo
 */
class ObjetivoEntidad
{

    public function __construct()
    {
        return;
    }

    /**
     * 
     * @param int $idObjetivo      Identificador del Objetivo
     * @param int $idEntidadOwn    Identificador del tipo de la entidadad a 
     *                              la que pertenece el objetivo
     * @param int $published       Published gestion eliminado logico.
     */
    public function saveObjEnt( $idObjetivo, $idEntidadOwn, $published )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $tbObjEnt->saveObjEnt( $idObjetivo, $idEntidadOwn, $published );
    }

    /**
     * 
     * @param type $idObjetivo
     * @param type $idEntidadOwn
     * @param type $published
     */
    public function updObjEnt( $idObjetivo, $idEntidadOwn, $published )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $tbObjEnt->updObjEnt( $idObjetivo, $idEntidadOwn, $published );
    }

    /**
     * Recupera los objetivos de la entidad que se pida
     * @param int       $tpoEntidad  Identificador del tipo de entidad
     * @return array
     */
    public function getObjetivosByTpoEntidad( $tpoEntidad )
    {
        $lstObjetivos = array();
        switch( $tpoEntidad ){
            case 1:     //PROGRAMA
                $lstObjetivos = $this->_getObjetivosProgramas();
            break;
        
            case 2:     //PROYECTO
                $lstObjetivos = $this->_getObjetivosProyectos();
            break;
        
            case 3:     //CONVENIOS
                $lstObjetivos = $this->_getObjetivosConvenios();
            break;
                
            case 7:     //UNIDADES DE GESTION 
                $lstObjetivos = $this->_getObjetivosUnidadGestion();
            break;
        
            case 12:    //ECORAE 
                $lstObjetivos = $this->_getObjetivosECORAE();
            break;
        
            case 0:    //ECORAE 
                $lstObjetivos = $this->_getObjetivosECORAE();
            break;
        }

        return $lstObjetivos;
    }

    /**
     * 
     * RETORNA la lista de los objetivos de un proyecto
     * 
     * @return type
     */
    private function _getObjetivosProyectos()
    {
        $lstProyecto = $this->_getProyectos();
        if( count( $lstProyecto ) > 0 ){
            foreach( $lstProyecto as $proyecto ){
                $proyecto->lstObjetivos = $this->_getObjetivosProyecto( (int)$proyecto->intIdEntidad_ent );
            }
        }
        return $lstProyecto;
    }

    /**
     * 
     * RETORNA la lista de proyectos
     * 
     * @return type
     */
    private function _getProyectos()
    {
        $db = JFactory::getDBO();
        $tbPrograma = new ProyectosTableProyecto( $db );
        $lstProgramas = $tbPrograma->getProyectos();
        return $lstProgramas;
    }

    /**
     * 
     * RETORNA la lista de los objetivos de un proyecto dado el id de la entidad.
     * 
     * @param   type $idEntidad   Identificador de la entidad
     * @return  type array
     */
    private function _getObjetivosProyecto( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->getObjetivosProyecto( $idEntidad );
        return $lstObjetivos;
    }

    /**
     * 
     * Recupera los objetivos y sus alineaciones
     * 
     * @return type
     */
    private function _getObjetivosProgramas()
    {
        $lstProgramas = $this->_getProgramas();
        if( count( $lstProgramas ) > 0 ){
            foreach( $lstProgramas as $programa ){
                $programa->lstObjetivos = $this->_getObjetivosPrograma( (int)$programa->intIdEntidad_ent );
            }
        }
        return $lstProgramas;
    }

    /**
     * 
     * Recupera los programas
     * 
     * @return type
     */
    private function _getProgramas()
    {
        $db = JFactory::getDBO();
        $tbPrograma = new ProgramaTablePrograma( $db );
        $lstProgramas = $tbPrograma->getProgramas();
        return $lstProgramas;
    }

    /**
     *
     * Recupera los objetivos del programa
     * 
     * @param type $idEntidad
     * @return type
     */
    private function _getObjetivosPrograma( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->getObjetivosPrograma( $idEntidad );
        return $lstObjetivos;
    }

    /**
     * 
     * RECUPERA los convenios del objetivo
     * 
     * @return type
     */
    private function _getObjetivosConvenios()
    {
        $lstConvenios = $this->_getConvenios();
        if( count( $lstConvenios ) > 0 ){
            foreach( $lstConvenios as $convenio ){
                $convenio->lstObjetivos = $this->_getObjetivosConvenio( $convenio->idEntidad );
            }
        }
        return $lstConvenios;
    }

    /**
     * 
     * Recupera la LISTA de CONVENIOS 
     * 
     * @return type
     */
    private function _getConvenios()
    {
        $db = JFactory::getDBO();
        $tbContrato = new ContratosTableContrato( $db );
        $lstConvenios = $tbContrato->getConvenios();
        return $lstConvenios;
    }

    /**
     * 
     * RECUPERA los OBJETIVOS de un CONVENIO dada la ENTIDAD del convenio 
     * 
     * @param   int     $idEntidad   Identificado de la entidad
     * @return  array   array
     */
    private function _getObjetivosConvenio( $idEntidad )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->getObjetivosConvenios( $idEntidad );
        return $lstObjetivos;
    }

    /**
     * 
     * Recupera los objetivos cuando se pide los objetivos del ECORAE
     * 
     * @return type
     */
    private function _getObjetivosECORAE()
    {
        $lstPeiVieg = array();

        //PEI VIGENTE
        $oPlanIstitucion = new PlanInstitucion( 0 );
        $peiVig = $oPlanIstitucion->getPeiVigente();
        if( is_object( $peiVig ) ){
            $peiVig->lstObjetivos = $this->getObjetivosPlan( $peiVig );
        }

        // para poder trabajar en el javascript
        $lstPeiVieg[] = $peiVig;

        return $peiVig;
    }

    /**
     * 
     * RECUPERA los OBJETIVOS de un PLAN
     * 
     * @param type $pei
     */
    private function getObjetivosPlan( $plan )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->getObjetivosPlanVigente( $plan->idPln, $plan->tipoPln );
        return $lstObjetivos;
    }

    /**
     * 
     * RECUPERA las UNIDADES DE GESTION y sus OBJETIVOS
     * 
     * @return type
     */
    private function _getObjetivosUnidadGestion()
    {
        $lstUndGes = $this->_getUnidadesGestion();
        if( count( $lstUndGes ) > 0 ){
            foreach( $lstUndGes AS $undGes ){
                $undGes->lstObjetivos = $this->_getObjetivosByUndGes( $undGes->idEntidad );
            }
        }
        return $lstUndGes;
    }

    /**
     *
     *  RECUPERA la unidad UNIDAD de GESTION 
     * 
     * @return type
     */
    private function _getUnidadesGestion()
    {
        $lstUndGes = array();
        $db = JFactory::getDBO();
        $tbObjOpe = new JTableUnidadGestion( $db );
        $lstUndGes = $tbObjOpe->getLstUndGestion();
        return $lstUndGes;
    }

    /**
     *  RECUPERA los OBJETIVOS por UNIDAD de GESTION 
     * @param object $undGes    unidad de gestion
     * @return type
     */
    private function _getObjetivosByUndGes( $undGes )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->getObjetivosUnidadGestion( $undGes );
        return $lstObjetivos;
    }

    /**
     * 
     * @param type $idObje
     * @param type $idEntidaOwn
     * @return type
     */
    public function deleteObjEntid( $idObje, $idEntidaOwn )
    {
        $db = JFactory::getDBO();
        $tbObjEnt = new jTableObjetivoEntidad( $db );
        $lstObjetivos = $tbObjEnt->deleteObjEntid( $idObje, $idEntidaOwn );
        return $lstObjetivos;
    }

}