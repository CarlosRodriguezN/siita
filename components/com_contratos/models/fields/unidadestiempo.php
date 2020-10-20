<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldUnidadesTiempo extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'UnidadesTiempo';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   um.intCodigo_unimed as id, 
                            UPPER( CONCAT( "(", um.strSimbolo_unimed, ") ", um.strDescripcion_unimed ) ) as nombre' );
        $query->from( '#__gen_unidad_medida um' );
        $query->where( 'um.intId_tum = 1' );
        
        $db->setQuery((string)$query);
        
        $db->query();
        
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
        
        return $options;
    }
}