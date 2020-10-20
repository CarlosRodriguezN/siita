<?php

defined( '_JEXEC' ) or die;

class modMapaGoogleMaps
{
    public function yaCargado()
    {
        $ban = false;
        $document = JFactory::getDocument();
        $headData = $document->getHeadData();
        
        foreach( array_keys( $headData["scripts"] ) as $script ){
            if(stristr( $script, 'maps' ) ){
                $ban = true;
            }
        }
        
        return $ban;
    }
}