<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo JText::_('COM_ADMINMAPAS_EDICION_PROYECTO') ?> </h2>
        </div>  
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_('index.php?option=com_adminmapas&layout=edit&intCodigo_shp_ecorae=' . (int) $this->item->intCodigo_shp_ecorae); ?>" method="post" name="adminForm" id="mapa-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>Información Mapa</legend>

                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('essential') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>

            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>Shape</legend>
                    <div id="queue"></div>
                    <input id="shape_upload" name="fileUpload" type="file" multiple="true">
                </fieldset>
            </div>            
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>Imagen</legend>
                    <div id="queue"></div>
                    <input id="image_upload" name="fileUpload" type="file" multiple="true">
                </fieldset>
            </div> 
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <a  class="modal" href="<?php echo JURI::root() ?>components/com_adminmapas/mapasInfo/images/<?php echo ($this->item->intCodigo_wms == 0) ? 'default.png' : $this->item->intCodigo_wms . ".png" ?>"> 
                        <img style="width: 200xp; 
                             height: 250px" 
                             src="<?php echo JURI::root() ?>components/com_adminmapas/mapasInfo/images/<?php echo ($this->item->intCodigo_wms == 0) ? 'default.png' : $this->item->intCodigo_wms . ".png" ?>"
                             >
                    </a>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="ecoraemapa.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>