<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
class mapaViewmapa extends JView
{
	public function display($tpl = null)
	{
		JHTML::_( 'behavior.mootools' );
                //  $this->document->addScript("http://maps.google.com/maps/api/js?sensor=false&language=es");
                $this->document->addScript("https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry");
                
                $this->document->addScript("components/com_mapa/assets/wms/wms.js");
                $this->document->addScript("components/com_mapa/assets/mapa.js");
               // $this->document->addScript("components/com_mapa/assets/mapa.css");
                $this->document->addScript("http://google-maps-utility-library-v3.googlecode.com/svn/trunk/arcgislink/src/arcgislink_compiled.js");
                parent::display($tpl);     
	}
}
?>