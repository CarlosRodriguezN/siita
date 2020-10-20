<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldMeses extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'meses';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();

        $options = array();
       
                $options[] = JHtml::_( 'select.option',strip_tags(1),strip_tags('Enero'));
                $options[] = JHtml::_( 'select.option',strip_tags(2),strip_tags('Febrero'));
                $options[] = JHtml::_( 'select.option',strip_tags(3),strip_tags('Marzo'));
                $options[] = JHtml::_( 'select.option',strip_tags(4),strip_tags('Abril'));
                $options[] = JHtml::_( 'select.option',strip_tags(5),strip_tags('Mayo'));
                $options[] = JHtml::_( 'select.option',strip_tags(6),strip_tags('Junio'));
                $options[] = JHtml::_( 'select.option',strip_tags(7),strip_tags('Julio'));
                $options[] = JHtml::_( 'select.option',strip_tags(8),strip_tags('Agosto'));
                $options[] = JHtml::_( 'select.option',strip_tags(9),strip_tags('Septiembre'));
                $options[] = JHtml::_( 'select.option',strip_tags(10),strip_tags('Octubre'));
                $options[] = JHtml::_( 'select.option',strip_tags(11),strip_tags('Noviembre'));
                $options[] = JHtml::_( 'select.option',strip_tags(12),strip_tags('Diciembre'));
        
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}