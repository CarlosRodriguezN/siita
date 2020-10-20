<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 *  HelloWorld Form Field class for the HelloWorld component
 * 
 */
class JFormFieldUnidadesGestion extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'unidadesgestion';
    protected $itemUndGestion;
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $idFuncionario = $this->form->getField('intCodigo_fnc')->value;

        if ($idFuncionario <> 0) {
            //  Obtengo el ID del tipo de objetivo
            $dtaUg = $this->_getIdUG( $idFuncionario );
            
            //  Seteo al XML el valor del tipo de objetivo con la finalidad de 
            //  obtener una lista de tipos de objetivos que formen parte del tipo seteado
            $this->form->setFieldAttribute( 'intCodigo_ug', 'default', $dtaUg->id );
        }else{
            $dtaUG = new stdClass();
            $dtaUg->id = 0;
        }
        
        $query->select( '   t1.intCodigo_ug         AS idUndGestion, 
                            t1.tb_intCodigo_ug      AS undGestionPadre,
                            UPPER( t1.strNombre_ug )AS nombre, 
                            UPPER( t1.strAlias_ug ) AS alias,
                            t1.intTpoUG_ug          AS idTpoUG' );
        $query->from( '#__gen_unidad_gestion t1' );
        $query->where( 't1.published = 1' );
        $query->order( 't1.strNombre_ug' );
        
        $db->setQuery((string)$query);
        $db->query();
        
        if( $db->getNumRows() > 0 ){
            
            $options[] = JHtml::_(  'select.option', 
                                    0, 
                                    JText::_( 'COM_FUNCIONARIOS_FIELD_UNIDAD_GESTION_TITLE' ) );
            
            $lstUndGestion = $db->loadObjectList();
            $this->_getLstFinal( $lstUndGestion, $lstUndGestion, 0 );
            
            foreach( $this->itemUndGestion as $undGestion ){
                $nivel = '';
                
                if( $undGestion->nivel > 0 ){
                
                    for( $x = 0; $x < $undGestion->nivel; $x++ ){
                        $nivel .= '&#124;&#45;&#45;';
                    }
                }
                
                $nombreUG = ( $undGestion->idTpoUG == 1 )   ? $undGestion->nombre
                                                            : $undGestion->alias;

                $options[] = JHtml::_(  'select.option', 
                                        $undGestion->idUndGestion, 
                                        $nivel. $nombreUG );
            }
        }else{
            $options[] = JHtml::_(  'select.option', 
                                    0, 
                                    JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SINREGISTROS_TITLE' ) );
        }
        
        
        return JHTML::_(    'select.genericlist', 
                            $options, 
                            'jform_intCodigo_ug', 
                            'class="inputbox"', 
                            'value', 
                            'text', 
                            $dtaUg->id );
    }
    
    
    
    private function _getIdUG($idUgf) {
        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select("ugf.intCodigo_ug as id");
            $query->from("#__gen_ug_funcionario ugf");
            $query->where("ugf.intCodigo_fnc = {$idUgf}");
            $query->where("ugf.published = 1");

            $db->setQuery((string) $query);

            $result = ($db->loadObject() ) 
                        ? $db->loadObject() 
                        : false;

            return $result;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_unidadgestion.models.fiels.ugpadre.log.php');
            $log->add($e->getMessage(), JLog::ERROR, $e->getCode());
        }
    }
    
    
    /**
     *
     * Retorno una lista (array) de rubros hijo, de un determinado rubro padre
     * 
     * @param type $lstUndGestion   lista total de rubros
     * @param type $idUndGestion     Rubro Padre
     * 
     * @return type array       Lista de hijos
     *  
     */
    private function _getTieneHijos( $lstUndGestion, $idUndGestion )
    {
        $numRegistros = count( $lstUndGestion );
        $lstHijos = array();
        
        for( $x = 0; $x < $numRegistros ;$x++ ){
            if( (int)$lstUndGestion[$x]->undGestionPadre == (int)$idUndGestion ){
                $lstHijos[] = $lstUndGestion[$x];
            }
        }
        
        return $lstHijos;
    }
    
    /**
     *
     *  Verifico la existecia de un determinado rubro en la lista ordenada 
     *  de rubros padre e hijo
     * 
     *  Con la finalidad de controlar una sobre validacion de subros al momento de construir
     *  la lista ordenada de rubros, padre e hijo
     * 
     *  @param type $idUndGestion    Identificador de Rubro
     * 
     *  @return boolean         Verdadero si existe, Falso si no existe
     * 
     */
    private function _existeItem( $idUndGestion )
    {
        $numRegistros = count( $this->itemUndGestion );

        for( $x = 0; $x < $numRegistros; $x++ ){
            if( $this->itemUndGestion[$x]->idUndGestion == $idUndGestion ){
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     *
     *  Genero la lista rubros padre e hijo
     * 
     * @param type $lstUndGestion   Lista de Unidades de Gestion registradas en el sistema
     * @param type $lstReferencia   Lista de Unidades de Gestion hijo
     * @param type $nivel           Nivel de profunfidad en el arbol, padre, hijo, nieto, etc
     */
    private function _getLstFinal( $lstUndGestion, $lstReferencia, $nivel )
    {
        $numRegistros = count( $lstReferencia );        
        for( $x = 0; $x < $numRegistros; $x++ ){
            //  Verifico si el item a validar no fue valorado anteriormente
            if( !$this->_existeItem( $lstReferencia[$x]->idUndGestion ) ){
                
                //  Verifico si el rubro a valorar tiene hijos
                $lstHijos = $this->_getTieneHijos( $lstUndGestion, $lstReferencia[$x]->idUndGestion );

                if( count( $lstHijos ) > 0 ){
                    $lstReferencia[$x]->nivel = $nivel;
                    $this->itemUndGestion[] = $lstReferencia[$x];
                    $this->_getLstFinal( $lstUndGestion, $lstHijos, $nivel+1 );
                }else{
                    $lstReferencia[$x]->nivel = $nivel;
                    $this->itemUndGestion[] = $lstReferencia[$x];
                }
            }
        } // Fin del For
    } // Fin del metodo

}