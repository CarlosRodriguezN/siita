<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft" >
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_MANTENIMIENTO_FIELD_AGD_ITEMS_LABEL' ) ?> </legend>
        <div id="nuevoItem" class="width-100 fltrt">
            <span> Nuevo Ítem </span>
            <span class="newItem" style="position: absolute">
                <img src="/media/system/images/mantenimiento/new.png" title="Nuevo">
            </span>
        </div> 
        <fieldset class="adminform">
            <div id="treeItems">
                
            </div>
            <div id="srItem" align="center" class="hide"> 
                <p> <?php echo JText::_( 'COM_MANTENIMIENTO_SIN_REGISTROS' ); ?> </p> 
            </div>
        </fieldset>
    </fieldset>
</div>