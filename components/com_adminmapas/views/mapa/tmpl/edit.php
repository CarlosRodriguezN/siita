<?php
    // No direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
    JHtml::_( 'behavior.tooltip' );
    JHtml::_( 'behavior.formvalidation' );
?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo ($this->item->intCodigo_wms == 0)   ? JText::_( 'COM_ADMINMAPAS_GESTION_ADD_MAPA' ) 
                                                                : JText::_( 'COM_ADMINMAPAS_GESTION_EDIT_MAPA' ); ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_( 'index.php?option=com_adminmapas&layout=edit&intCodigo_wms=' . ( int ) $this->item->intCodigo_wms ); ?>" method="post" name="adminForm" id="mapa-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_ADMINMAPAS_INFOMAPAS_MAPAS' ); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach( $this->form->getFieldset( 'essential' ) as $field ): ?>
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
                    <legend>&nbsp;<?php echo JText::_( 'COM_ADMINMAPAS_INFOCAPAS_MAPAS' ); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach( $this->form->getFieldset( 'urlsmapas' ) as $field ): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>                            
                    </ul>

                    <div class="clr"></div>
                    <input id="btnLayersMapa" type="button" value="Cargar Capas">
                    <div class="clr"> <hr> </div>
                    <div class="clr">&nbsp;</div>

                    <div id="infoCapas" >

                        <?php if( ($this->layers ) ): ?>
                            <?php foreach( $this->layers as $layer ): ?>
                                <div class="clr"></div>
                                <div>
                                    <input  class ="chkCapas" 
                                            type="checkbox" 
                                            <?php echo ( ( $layer->published == "1" ) ? ' checked="checked "' : '' ); ?>
                                            class="chkCapas" 
                                            name="chkCapas[]" 
                                            value= "<?php echo ($layer->intCodigoMapLayers . '-' . $layer->strNombreLayer . '-' . $layer->strTitleLayer); ?>" >
                                            <?php echo ($layer->strTitleLayer); ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="clr"></div>
                </fieldset>
            </div>
            <div>
                <input type="hidden" name="task" value="mapa.edit" />
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </form>

    </div>
</div>