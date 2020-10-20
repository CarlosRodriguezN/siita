<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * HelloWorlds Controller
 */
class plannacionalControllerPeriodicidades extends JControllerAdmin {

   //  Usamos para las cadenas de idioma
   protected $text_prefix = 'COM_PLANNACIONAL_PERIODICIDADES';

   public function getModel($name = 'Periodicidad', $prefix = 'plannacionalModel') {
      $model = parent::getModel($name, $prefix, array('ignore_request' => true));
      return $model;
   }

}