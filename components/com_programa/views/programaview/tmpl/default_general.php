<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="adminform width-100" >
    <div class="width-60 fltrt">
        <?php echo $this->loadTemplate('mapa'); ?>
    </div>
    <div class="width-40 fltleft">
        <fieldset class="adminform">
            <legend><?php echo $this->general->strNombre_prg ?></legend>
            <div style="height: 400px" class="width-100 fltleft scroll">
                <p><?php echo $this->general->strAlias_prg ?></p>
                <img alt="<?php echo $this->general->strDescripcion_prg ?>" 
                     src="<?php echo "images" . DS . "stories" . DS . "programa" . DS . "imagenes" . DS . $this->general->intCodigo_prg . ".png" ?>" 
                     style="margin-left: 10px; 
                     margin-right: 10px; 
                     float: left;
                     width: 100px; 
                     height: 100px; ">
                <p><?php echo $this->general->strDescripcion_prg ?></p>
            </div>
        </fieldset>
    </div>
</div>