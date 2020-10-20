<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldCantones extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'Cantones';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $options = "SELECCIONE CANTON";
        
        if( !empty( $this->value ) ){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
        
            $query->select( 'distinct dpa.strCodCanton_dpa as id, 
                            upper( dpa.strCanton_dpa ) as nombre' );
            $query->from( '#__PRM_DPA dpa' );
            $query->where( 'dpa.strCodProvincia_dpa = '. substr( $this->value, 0, 2 ) );

            echo $query->__toString(); exit;
            
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
        }
        
        return $options;
    }
}