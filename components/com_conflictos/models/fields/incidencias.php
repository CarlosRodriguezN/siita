<?php

// No direct access to this file
defined( '_JEXEC' ) or die;

// import the list field type
jimport( 'joomla.form.helper' );
JFormHelper::loadFieldClass( 'list' );

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldIncidencias extends JFormFieldList
{

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'incidencias';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        $query->select( 'inc.intId_inc AS id, 
                         upper( inc.strNombre_inc ) AS nombre' );
        $query->from( '#__gc_incidencia AS inc' );
        $query->where( 'inc.published = 1' );

        $db->setQuery( (string) $query );
        $messages = ($db->loadObjectList()) ? $db->loadObjectList() : false;
        $options = array( );
        if( $messages ) {
            foreach( $messages as $message ) {
                $options[] = JHtml::_( 'select.option', strip_tags( $message->id ), strip_tags( $message->nombre ) );
            }
        }

        $options = array_merge( parent::getOptions(), $options );

        return $options;
    }

}