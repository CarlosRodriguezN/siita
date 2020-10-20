<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldInstituciones extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'instituciones';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   t1.intCodigo_ins            AS id,
                            UPPER( t1.strNombre_ins )   AS nombre,
                            t1.strAlias_ins             AS alias' );

        $query->from( '#__gen_institucion t1' );
        $query->where( 't1.intCodigo_ins > 0' );
        $query->where( 't1.published = 1' );
        $query->order( 't1.strNombre_ins' );
        
        $db->setQuery( (string)$query );

        $query->query();
        
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