<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * HelloWorlds Controller
 */
class plannacionalControllerObjetivosnacionales extends JControllerAdmin {

   //  Usamos para las cadenas de idioma
   protected $text_prefix = 'COM_PLANNACIONAL_OBJETIVONACIONALS';

   public function getModel($name = 'Objetivonacional', $prefix = 'plannacionalModel') {
      $model = parent::getModel($name, $prefix, array('ignore_request' => true));
      return $model;
   }

}