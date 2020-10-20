<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');

JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_programa' . DS . 'tables');

/**
 * Modelo del componente Programa
 */
class ProgramaModelProgramaView extends JModelAdmin {

    private $_idPrograma;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Programa', $prefix = 'ProgramaTable', $config = array()) {
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
// Get the form.
        $form = $this->loadForm('com_programa.programa', 'programa', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = JFactory::getApplication()->getUserState('com_programa.edit.programas.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Retorna la información general de un programa.
     * @return type
     */
    public function getDataPrograma() {
        $idPrograma = JRequest::getVar("idPrograma");
        $tbPrograma = $this->getTable("Programa", 'ProgramaTable');
        $data = $tbPrograma->getProgramaByID($idPrograma);
        return $data;
    }

    /**
     * Recupera la lista de proyectos de un programa.
     * @return type
     */
    public function getProyectosPrograma() {
        $idPrograma = JRequest::getVar("idPrograma");
        $tbProyecto = $this->getTable("Proyecto", 'ProgramaTable');

        $proyectos = $tbProyecto->getProyectosByPrograma($idPrograma);
        if ($proyectos) {
            foreach ($proyectos AS $proyecto) {

                $lstUnidadesTerritotiale = $tbProyecto->getUbicacionesGeograficaProyecto($proyecto->intCodigo_pry);

                $aux = array();
                if ($lstUnidadesTerritotiale) {
                    foreach ($lstUnidadesTerritotiale AS $key => $unidadTerritorial) {
                        $aux[$key] = $this->getLstUnidadTerritorial(($unidadTerritorial->intID_ut));
                    }
                }
                $proyecto->UnidadTerr = $aux;
                $proyecto->imagenes = $tbProyecto->getImagenesProyecto($proyecto->intCodigo_pry);
            }
        }
        return $proyectos;
    }

    /**
     * 
     * @param type $idPrograma
     * @return type
     */
    public function getUbicacionGeografica($idPrograma) {
        $tbProyecto = $this->getTable("Proyecto", 'ProgramaTable');
        $unidadesTerritoriales = $tbProyecto->getUbicacionesGeograficaProyecto($idPrograma);

        return $unidadesTerritoriales;
    }

    /**
     * 
     * @param type $idProyecto
     * @return type
     */
    public function getLstUnidadTerritorial($idProyecto) {
        $tbUnidadTerrito = $this->getTable("UnidadTerritorial", 'ProgramaTable');
        $unidadTerritorial = $tbUnidadTerrito->dtaDPA($idProyecto);
        return $unidadTerritorial;
    }

    /**
     *  Retorna la lista de indicadores de un programa. 
     * @return type
     */
    public function getIndicadoresPrograma() {

        $idPrograma = JRequest::getVar("idPrograma");
        $tbPrograma = $this->getTable("Programa", "ProgramaTable");
        $programa = $tbPrograma->getProgramaByID($idPrograma);
        $lstIndicadores = false;

        if ($programa) {
            $tbIndicadorEntidad = $this->getTable("EntidadIndicador", "ProgramaTable");
            $lstIndicadores = $tbIndicadorEntidad->getIndicadoresEntidad($programa->intIdEntidad_ent);
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
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ProgramaTable");
        $variables = $tbIndicadorEntidad->getLstVariablesIndicador($idIndicador);
        return $variables;
    }

    /**
     *  Recupera el seguimiento de una variable
     * @param type $idVariableIndicador
     * @return type
     */
    public function getSeguimientoVariableIndicador($idVariableIndicador) {
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ProgramaTable");
        $variables = $tbIndicadorEntidad->getSeguimientoVariableIndicador($idVariableIndicador);
        return $variables;
    }

}