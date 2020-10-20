<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables');

/**
 * Modelo del componente Programa
 */
class ProyectosModelProyectoView extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Proyecto', $prefix = 'ProyectosTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_programa.proyectoview', 'proyectoview', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_programa.edit.proyectoview.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Retorna el proyecto.
     * @return type
     */
    public function getDataProyecto() {
        $idProyecto = JRequest::getVar("idProyecto");
        $tbProyecto = $this->getTable("Proyecto", "ProyectosTable");
        $oProyecto = $tbProyecto->getDataProyecto($idProyecto);
        return $oProyecto;
    }

    /**
     * Recupera la lista de contratos que tiene un proyecto.
     * @return type
     */
    public function getContratosProyecto() {
        $idProyecto = JRequest::getVar("idProyecto");
        $tbContratos = $this->getTable("Contrato", "ProyectosTable");
        $lstContratos = $tbContratos->getContratosProyecto($idProyecto);
        if ($lstContratos) {
            foreach ($lstContratos AS $contrato) {
                $contrato->ubicaciones = $this->getUbicacionContrato($contrato);
            }
        }
        return $lstContratos;
    }

    /**
     * Retorna las ubicaciones territoriales de un contrato 
     * @param object $contrato  Objecto contrato.
     * @return array 
     */
    public function getUbicacionContrato($contrato) {
        $tbContratos = $this->getTable("UbiGeoCtr", "ProyectosTable");
        $lstUniTerr = $tbContratos->getUbicacionContrato($contrato->intIdContrato_ctr);
        if ($lstUniTerr) {
            foreach ($lstUniTerr AS $uniTerr) {
                $uniTerr->dpaUbGeo = $this->getDpaUbiGeo($uniTerr);
            }
        }

        return $lstUniTerr;
    }

    /**
     * Recupera la ubicaicion geografica de una dpa
     * @param type $uniTerr
     * @return type
     */
    public function getDpaUbiGeo($uniTerr) {
        $tbContratos = $this->getTable("UnidadTerritorial", "ProyectosTable");
        $dpa = $tbContratos->dtaDPAContratos($uniTerr->intID_ut);
        return $dpa;
    }

    /**
     *  Retorna la lista de indicadores de un programa. 
     * @return Array
     */
    public function getIndicadoresProyecto() {

        $idProyecto = JRequest::getVar("idProyecto");
        $tbContrato = $this->getTable("Proyecto", 'ProyectosTable');
        $proyecto = $tbContrato->getDataProyecto($idProyecto);
        $lstIndicadores = false;

        if ($proyecto[0]) {
            $tbIndicadorEntidad = $this->getTable('EntidadIndicador', 'ProyectosTable');
            $lstIndicadores = $tbIndicadorEntidad->getIndicadoresEntidad($proyecto[0]->intIdEntidad_ent);
            if ($lstIndicadores) {
                foreach ($lstIndicadores AS $indicador) {
                    $indicador->variables = $this->getVariablesIndicador($indicador->idIndicador);
                    if ($indicador->variables)
                        foreach ($indicador->variables AS $variable) {
                            $variable->seguimineto = $this->getSeguimientoVariableIndicador($variable->idVariableIndicador);
                        }
                }
            }
        }
        return $lstIndicadores;
    }

    /**
     *  Recupera la lista de variaonels de un indicador.
     * @param type $idIndicador
     * @return type
     */
    public function getVariablesIndicador($idIndicador) {
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ProyectosTable");
        $variables = $tbIndicadorEntidad->getLstVariablesIndicador($idIndicador);
        return $variables;
    }

    /**
     *  Recupera el seguimiento de una variable
     * @param type $idVariableIndicador
     * @return type
     */
    public function getSeguimientoVariableIndicador($idVariableIndicador) {
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ProyectosTable");
        $variables = $tbIndicadorEntidad->getSeguimientoVariableIndicador($idVariableIndicador);
        return $variables;
    }

}