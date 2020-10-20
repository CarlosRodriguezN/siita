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
class JFormFieldSectores extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'Sectores';
    protected $modelPry = '';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()
    {
        $modelPry = JModel::getInstance( 'proyecto', 'ProyectosModel' );
        
        $idMacroSector = $this->form->getField( 'macrosector' )->value;
        $idSector = $this->form->getField( 'sector' )->value;
        $idSubSector = (int)$this->form->getField( 'inpCodigo_subsec' )->value;
        //  Obtengo el id de la version vigente de sectores de intervencion 
        $idSI = (int)$this->form->getField( 'intIdStr_intervencion' )->value;
        $SI = ( $idSI > 0 ) ? $idSI : (int)$modelPry->getSctIntrvVigente()->id;
        
        if ( $idMacroSector === '' ) {
            if( $idSubSector > 0 ){
                //  Obtengo el valor del subsector
                $idSector = $this->_getIdSector( $idSubSector );
                //  Seteo al XML el valor del Sector con la finalidad de obtener  
                //  una lista de SubSectores que formen parte del sector seteado
                $this->form->setValue( 'sector', null, $idSector );
            }
        } 
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select( 'sec.inpCodigo_sec as id, 
                        UPPER( sec.strDescripcion_sec ) as nombre' );
        $query->from( '#__gen_sector sec' );
        $query->where( 'sec.intId_si = ' . $SI );
        
        if ( $idMacroSector !== '' && $idMacroSector !== 0 ) {
            $query->where( 'sec.intId_macrosector = ' . $idMacroSector );
        }
        
        $query->order( 'sec.strDescripcion_sec' );
        $db->setQuery( (string)$query );
        $db->query();

        $messages = $db->loadObjectList();
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        
        $options  .= '<option value ="0">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SECTOR_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idSector == $value->id ) ? 'selected'
                                                    : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        $options .= "</select>";
        
        return $options;
    }
    
    /**
     *  Retorna el identificador del sector dado un subsector
     * @param type $idSubSector identificador del subsector
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
}