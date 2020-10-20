<?php

/**
 * @version		$Id: helloworlds.php 59 2010-11-27 14:17:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controlleradmin library
jimport( 'joomla.application.component.controlleradmin' );

/**
 * HelloWorlds Controller
 */
class IndicadoresControllerIndicadores extends JControllerAdmin
{

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel( $name = 'Indicador', $prefix = 'IndicadoresModel' )
    {
        $model = parent::getModel( $name, $prefix, array('ignore_request' => true) );
        return $model;
    }

}
