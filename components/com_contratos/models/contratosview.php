<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
jimport('ecorae.uploadfile.upload');
JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_contratos' . DS . 'tables');

/**
 * Modelo tipo obra
 */
class contratosModelContratosView extends JModelAdmin {

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Atributo', $prefix = 'contratosTable', $config = array()) {
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
        $form = $this->loadForm('com_contratos.atributo', 'atributo', array('control' => 'jform', 'load_data' => $loadData));

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
        // Check the session for previously entered form data.
        $data = JFactory::AtributogetApplication()->getUserState('com_contratos.edit.atributo.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    public function getContratoData() {
        $idContrato = JRequest::getVar('idContrato');
        $tbContrato = $this->getTable('Contrato', 'ContratosTable');
        $contrato = $tbContrato->getContratoById($idContrato);
        return $contrato;
    }

    /**
     *  Obtine una lista de graficos de un contrato con sus coordenadas.
     * @return type
     */
    public function getGraficosContrato() {
        $idContrato = JRequest::getVar('idContrato');

        $ltsGraficos = false;

        if ((int) $idContrato != 0) {
            $tbGrafico = $this->getTable('Grafico', 'ContratosTable');
            $ltsGraficos = $tbGrafico->getGraficosContrato($idContrato);

            if ($ltsGraficos) {
                foreach ($ltsGraficos AS $grafico) {
                    $grafico->lstCoordenadas = $this->getCoordenadasGrafico($grafico->idGrafico);
                }
            }
        }

        return $ltsGraficos;
    }

    /**
     *  Obtiene una lista de coordenadas de un grafico.
     * @param   int   $idGrafico    Identificador del grafico.
     * @return array                Lista de objetos coordenada.
     */
    public function getCoordenadasGrafico($idGrafico) {
        $tbCoordenada = $this->getTable('Coordenada', 'ContratosTable');
        $ltsGraficos = $tbCoordenada->getCoordenadasGrafico($idGrafico);
        return $ltsGraficos;
    }

    /**
     *  Retorna la lista de indicadores de un programa. 
     * @return type
     */
    public function getIndicadoresContrato() {

        $idContrato = JRequest::getVar("idContrato");
        $tbContrato = $this->getTable("Contrato", 'ContratosTable');
        $contrato = $tbContrato->getContratoById($idContrato);
        $lstIndicadores = false;

        if ($contrato) {
            $tbIndicadorEntidad = $this->getTable("EntidadIndicador", 'ContratosTable');
            $lstIndicadores = $tbIndicadorEntidad->getIndicadoresEntidad($contrato->intIdentidad_ent);
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
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ContratosTable");
        $variables = $tbIndicadorEntidad->getLstVariablesIndicador($idIndicador);
        return $variables;
    }

    /**
     *  Recupera el seguimiento de una variable
     * @param type $idVariableIndicador
     * @return type
     */
    public function getSeguimientoVariableIndicador($idVariableIndicador) {
        $tbIndicadorEntidad = $this->getTable("VariableIndicador", "ContratosTable");
        $variables = $tbIndicadorEntidad->getSeguimientoVariableIndicador($idVariableIndicador);
        return $variables;
    }

}