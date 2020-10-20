<?php

// No direct access to this file
defined( '_JEXEC' ) or die;

// import the list field type
jimport( 'joomla.form.helper' );
JFormHelper::loadFieldClass( 'list' );

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldEnfoques extends JFormFieldList
{

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'enfoques';
    protected $itemEnfoque;
    private $_isPadre;

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getInput()
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $padre = 0;
            $idEnfoque = $this->form->getField( 'intId_enfoque' )->value;

            if ( $idEnfoque <> 0 ){
                //  Obtengo el valor del subsector
                $this->_padre = $this->_getIdPadre( $idEnfoque );

                //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
                //  que formen parte del sector seteado
                $this->form->setValue( 'intId_enfoquePadre', null, $padre );
            }


            $query->select( '   t1.intId_enfoque                AS id, 
                                upper( t1.strNombre_enfoque )   AS nombre, 
                                t1.intId_enfoquePadre           AS idEnfoquePadre' );
            $query->from( '#__gen_enfoque t1' );
            $query->where( 't1.intId_enfoque <> 0' );
            $query->where( 't1.published = 1' );
            
            $db->setQuery( $query );

            $lstEnfoques = $db->loadObjectList();
            
            if( count( $lstEnfoques ) ){
                $options = $this->_getDtaCombo( $lstEnfoques );
            }
            
            return $options;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_mantenimiento.models.fiels.enfoques.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    private function _getIdPadre( $idEnfonque )
    {
        try{
            $db = JFactory::getDBO();
            $query = $db->getQuery( true );

            $query->select( "a.intId_enfoquePadre as id" );
            $query->from( "#__gen_enfoque a" );
            $query->where( "a.intId_enfoque = '{$idEnfonque}'" );

            $db->setQuery( (string)$query );

            $result = $db->loadObject();

            return $result->id;
        } catch ( Exception $e ){
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'mod_mantenimiento.models.fiels.enfoques.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    
    
    
    private function _getDtaCombo( $lstEnfoques )
    {    
        $this->_getLstFinal( $lstEnfoques, $lstEnfoques, 0 );
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
        
        if( count( $this->itemEnfoque ) ){
            $options .= '   <option value="0">' . JText::_( 'COM_MANTENIMIENTO_FIELD_ENFOQUE_PADRE_TITLE' ) . '</option>';

            foreach ( $this->itemEnfoque as $enfoque ){
                $nivel = '';

                if( $enfoque->nivel > 0 ){
                
                    for( $x = 0; $x < $enfoque->nivel; $x++ ){
                        $nivel .= '&#124;&#45;&#45;';
                    }

                }
                
                $selected = ( $this->_padre == $enfoque->id )   ? 'selected'
                                                                : '';

                $options .= '<option value="' . $enfoque->id . '" ' . $selected . '>' . $nivel . $enfoque->nombre . '</option>';
            }
        }else{
            $options .= '   <option value="0">' . JText::_( 'COM_MANTENIMIENTO_SIN_REGISTROS' ) . '</option>';
        }

        $options .= "</select>";
        
        return $options;
    }
    
    
    /**
     *
     * Retorno una lista (array) de rubros hijo, de un determinado rubro padre
     * 
     * @param type $lstEnfoques   lista total de rubros
     * @param type $idEnfoque     Rubro Padre
     * 
     * @return type array       Lista de hijos
     *  
     */
    private function _getTieneHijos( $lstEnfoques, $idEnfoque )
    {
        $numRegistros = count( $lstEnfoques );
        $lstHijos = array ();

        for ( $x = 0; $x < $numRegistros; $x++ ){
            if ( (int)$lstEnfoques[$x]->idEnfoquePadre == (int)$idEnfoque ){
                $lstHijos[] = $lstEnfoques[$x];
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
     *  @param type $idEnfoque    Identificador de Rubro
     * 
     *  @return boolean         Verdadero si existe, Falso si no existe
     * 
     */
    private function _existeItem( $idEnfoque )
    {
        $ban = false;
        $numRegistros = count( $this->itemEnfoque );

        for ( $x = 0; $x < $numRegistros; $x++ ){
            if ( $this->itemEnfoque[$x]->id == $idEnfoque ){
                $ban = true;
            }
        }

        return $ban;
    }

    /**
     *
     *  Genero la lista rubros padre e hijo
     * 
     * @param type $lstEnfoques     Lista de Unidades de Gestion registradas en el sistema
     * @param type $lstReferencia   Lista de Unidades de Gestion hijo
     * @param type $nivel           Nivel de profunfidad en el arbol, padre, hijo, nieto, etc
     */
    private function _getLstFinal( $lstEnfoques, $lstReferencia, $nivel )
    {
        $numRegistros = count( $lstReferencia );

        for ( $x = 0; $x < $numRegistros; $x++ ){
            //  Verifico si el item a validar no fue valorado anteriormente
            if ( !$this->_existeItem( $lstReferencia[$x]->id ) ){

                //  Verifico si el rubro a valorar tiene hijos
                $lstHijos = $this->_getTieneHijos( $lstEnfoques, $lstReferencia[$x]->id );

                if ( count( $lstHijos ) > 0 ){
                    $lstReferencia[$x]->nivel = $nivel;
                    $this->itemEnfoque[] = $lstReferencia[$x];
                    $this->_getLstFinal( $lstEnfoques, $lstHijos, $nivel + 1 );
                } else{
                    $lstReferencia[$x]->nivel = $nivel;
                    $this->itemEnfoque[] = $lstReferencia[$x];
                }
            }
        } // Fin del For
    }
}// Fin del metodo
