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
class JFormFieldSubSectores extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'subsectores';
    protected $modelPry = '';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions()
    {
        $modelPry = JModel::getInstance( 'proyecto', 'ProyectosModel' );
        
        $idSector = (int)$this->form->getField( 'sector' )->value;
        //  Obtengo el id de la version vigente de sectores de intervencion 
        $idSI = (int)$this->form->getField( 'intIdStr_intervencion' )->value;
        $SI = ( $idSI > 0 ) ? $idSI : (int)$modelPry->getSctIntrvVigente()->id;
        
        if( $idSector > 0 ){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( 'sub.inpCodigo_subsec as id, 
                            UPPER( sub.strDescripcion_subsec ) as nombre' );
            $query->from( '#__gen_subsector sub' );
            $query->where( "sub.inpCodigo_sec = '{$idSector}'" );
            $query->where( 'intId_si = ' . $SI );
            $query->order( 'sub.strDescripcion_subsec' );

            $db->setQuery( (string)$query );
            $messages = $db->loadObjectList();
            $options = array();

            if ($messages)
            {
                foreach($messages as $message) 
                {
                    $options[] = JHtml::_( 'select.option', 
                                            $message->id, 
                                            $message->nombre );
                }
            }
            
            $options = array_merge( parent::getOptions(), $options );
        } else {
            $options = JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SUBSECTOR_TITLE' );
        }
        
        return $options;
    }
}