<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldAtencionPriotarios extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'atencionpriotarios';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   gap.intId_dim as id,
                            upper( gap.strDescripcion_dim ) as nombre' );
        $query->from( '#__gen_dimension gap' );
        $query->where( 'gap.intId_enfoque = 7' );
        $query->where( 'gap.published = 1' );
        $query->order( 'gap.strDescripcion_dim' );

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