<?php
/**
 * Base Model for Base Component
 *
 * @package			Template
 * @subpackage	Components
 * @copyright		Copyright (C) 2007 - 2009 NawesCorp. All rights reserved.
 * @license			GNU/GPL
 */

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Base Model
 *
 * @package    Template
 * @subpackage Components
 */
class BaseModelBase extends JModel
{
    /**
    * Gets the greeting
    * @return string The greeting to be displayed to the user
    */
		function getMessage()
		{
			 $db =& JFactory::getDBO();

			 $query = 'SELECT message FROM #__ns_base';
			 $db->setQuery( $query );
			 $greeting = $db->loadResult();

			 return $greeting;
		}
}
