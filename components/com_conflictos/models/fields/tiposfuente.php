<?php

// No direct access to this file
defined( '_JEXEC' ) or die;

// import the list field type
jimport( 'joomla.form.helper' );
JFormHelper::loadFieldClass( 'list' );

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposFuente extends JFormFieldList
{

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'tiposfuente';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        $query->select( 'ca.intId_tf AS id, 
                         upper( ca.strDescripcion_tf ) AS nombre' );
        $query->from( '#__gc_tipo_fuente AS ca' );
        $query->where( 'ca.published = 1' );

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