<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

JLoader::import('joomla.application.component.model');
JLoader::import( 'proyecto', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_proyectos' . DS . 'models' );

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldMacroSectores extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'MacroSectores';
    protected $modelPry = '';
    
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()
    {
        $modelPry = JModel::getInstance( 'proyecto', 'ProyectosModel' );
        //  Inicializo el MacroSector
        $idMacroSector = 0;
        $idSector = 0;
        //  Obtengo el SubSector
        $idSubSector = (int)$this->form->getField( 'inpCodigo_subsec' )->value;
        //  Obtengo el id de la version vigente de sectores de intervencion 
        $idSI = (int)$this->form->getField( 'intIdStr_intervencion' )->value;
        $SI = ( $idSI > 0 ) ? $idSI : (int)$modelPry->getSctIntrvVigente()->id;
        
        //  Si el valor de SubSector es diferente de cero (0), obtengo el "Sector"
        if( $idSubSector > 0 ){
            //  Obtengo el valor del sector
            $idSector = $this->_getIdSector( $idSubSector );
            if ( $idSector > 0 ) {
                //  Obtengo el valor del macrosector
                $idMacroSector = $this->_getIdMacroSector( $idSector );
            } 
        } 
        
        //  Seteo al XML el valor del Sector con la finalidad de obtener una lista de SubSectores
        //  que formen parte del sector seteado
        $this->form->setValue( 'sector', null, $idSector );
        $this->form->setValue( 'macrosector', null, $idMacroSector );

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( 'sec.intId_macrosector as id, 
                        UPPER( sec.strNombre_ms ) as nombre' );
        $query->from( '#__gen_macrosector sec' );
        $query->where( 'intId_si = ' . $SI );
        $query->order( 'sec.strNombre_ms' );

        $db->setQuery( (string)$query );
        $db->query();

        $messages = $db->loadObjectList();

        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';

        $options  .= '<option value ="0">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MACROSECTOR_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idMacroSector == $value->id ) ? 'selected'
                                                    : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        $options .= "</select>";

        return $options;
        
        
    }
    
    /**
     * 
     * Retorna el identificador del sector dado un subsector
     *  
     * @param type $idSubSector identificador del subsector
     * 
     */
    private function _getIdSector( $idSubSector )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( 'sub.inpcodigo_sec as id' );
        $query->from( '#__gen_subsector sub' );
        $query->where( 'sub.inpcodigo_subsec = '. $idSubSector  );

        $db->setQuery( (string)$query );
        $db->query();
        
        $idSector = ( $db->getNumRows() > 0 ) ? (int)$db->loadObject()->id : 0;

        return $idSector;
    }
    
    /**
     *  Retorna el identificador del macrosector dado un sector
     * @param type $idSector
     * @return type
     */
    private function _getIdMacroSector( $idSector )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( 'sub.intId_macrosector as id' );
        $query->from( '#__gen_sector sub' );
        $query->where( 'sub.inpCodigo_sec = '. $idSector  );

        $db->setQuery( (string)$query );
        $db->query();
        
        $idMacroSector = ( $db->getNumRows() > 0 ) ? (int)$db->loadObject()->id : 0;

        return $idMacroSector;
    }
}