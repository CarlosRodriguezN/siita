<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposUnidadesMedidas extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'TiposUnidadesMedidas';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        echo $query->__toString(); exit;
        
        $query->select( '   tum.intId_tum as id,
                            tum.strDescripcion_tum as nombre' );
        $query->from( '#__gen_tipo_unidad_medida tum' );
        $query->where( 'tum.published = 1' );
        $query->order( 'strDescripcion_tum' );

        $db->setQuery((string)$query);
        
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