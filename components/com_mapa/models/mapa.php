<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');
class mapaModelMapa extends JModelItem
{
        protected $msg;
 	public function getMsg() 
	{
		if (!isset($this->msg)) 
		{
			$this->msg = 'Mapas de google ';
                }
		return $this->msg;
        }
}
?>
