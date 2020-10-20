<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldVacios extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'vacios';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();

        $options = array();
       
                $options[] = JHtml::_( 'select.option', 
                                        strip_tags(0), 
                                        strip_tags('Sin data disponible'));
        
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}