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
            <h2> <?php echo ($this->item->intCodigo_wms == 0) ? JText::_('COM_ADMINMAPAS_GESTION_ADD_MAPA_ECORAE') : JText::_('COM_ADMINMAPAS_GESTION_EDIT_MAPA_ECORAE') ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_('index.php?option=com_adminmapas&layout=edit&intCodigo_shp_ecorae=' . (int)$this->item->intCodigo_shp_ecorae); ?>" method="post" name="adminForm" id="mapa-form" class="form-validate" enctype="multipart/form-data" >
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>Información Mapa</legend>

                    <ul class="adminformlist">
                        <?php foreach( $this->form->getFieldset('essential') as $field ): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            </div>

            <!-- Carga de Shape -->
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_ADMINMAPAS_SHAPE') ?></legend>
                    <div id="queue"></div>
                    <input id="shape_upload" name="fileUpload" type="file" multiple="true">

                    <div class="clr"></div>
                    <div class="clr"></div>

                    <table id="lstShape" class="tablesorter width-100" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center" class="width-80"> <?php echo JText::_('COM_ADMINMAPAS_FIELD_IMAGEN_NOMBREIMG_TITLE') ?> </th>
                                <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_ADMINMAPAS_FIELD_IMAGEN_DELIMAGEN_TITLE' ) ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( count( $this->shape ) != 0 ): ?>
                                <?php $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'mapShapes'. DS . $this->shape[0]["nameArchivo"]; ?>
                                <tr>
                                    <!-- Nombre del Icono -->
                                    <td align="center">
                                        <?php echo $this->shape[0]["nameArchivo"]; ?>
                                    </td>
                                    
                                    <!-- DESCARGA de la imagen -->
                                    <td align="center">
                                        <a href="<?php echo $pathIcon; ?>">
                                            <?php echo JText::_('LB_DOWNLOAD'); ?>
                                        </a>
                                    </td>

                                    <!-- Eliminar el icono. -->
                                    <td align="center">
                                        <a href="#" class="elmIcon">
                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </fieldset>
            </div>

            <!--Carga de Imagen -->
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_('COM_ADMINMAPAS_IMAGEN') ?> </legend>
                    <div id="queue"></div>
                    <input id="image_upload" name="fileUpload" type="file" multiple="true">

                    <div class="clr"></div>
                    <div class="clr"></div>

                    <table id="lstImgCargadas" class="tablesorter width-100" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center" class="width-80"> <?php echo JText::_('COM_ADMINMAPAS_FIELD_IMAGEN_NOMBREIMG_TITLE') ?> </th>
                                <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_ADMINMAPAS_FIELD_IMAGEN_VISTA_TITLE' ) ?> </th>
                                <th align="center" class="width-10" > <?php echo JText::_('COM_ADMINMAPAS_FIELD_IMAGEN_DELIMAGEN_TITLE') ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( count( $this->imagenes ) != 0 ): ?>
                                <?php $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'mapImages'. DS . $this->shape[0]["nameArchivo"]; ?>
                                <tr>
                                    <!-- Nombre del Icono -->
                                    <td align="center">
                                        <?php echo $this->shape[0]["nameArchivo"]; ?>
                                    </td>

                                    <!-- Vista previa de la imagen -->
                                    <td align="center">
                                        <a href="<?php echo $pathIcon ?>" class="modal" > 
                                            <?php echo JText::_( 'LB_VER' ); ?>
                                        </a>
                                    </td>
                                    
                                    <!-- DESCARGA de la imagen -->
                                    <td align="center">
                                        <a href="<?php echo $pathIcon; ?>">
                                            <?php echo JText::_('LB_DOWNLOAD'); ?>
                                        </a>
                                    </td>

                                    <!-- Eliminar el icono. -->
                                    <td align="center">
                                        <a href="#" class="elmIcon">
                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </fieldset>
            </div> 

            <!-- GeoServicios -->
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_ADMINMAPAS_GEOSERVICIO') ?></legend>
                    <div id="queue"></div>
                    <input id="texto_upload" name="fileUpload" type="file" multiple="true">
                    
                    <div class="clr"></div>
                    <div class="clr"></div>

                    <table id="lstGeoServicios" class="tablesorter width-100" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center" class="width-80"> <?php echo JText::_('COM_ADMINMAPAS_FIELD_IMAGEN_NOMBREIMG_TITLE') ?> </th>
                                <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_ADMINMAPAS_FIELD_IMAGEN_DELIMAGEN_TITLE' ) ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( count($this->geoServicios) != 0 ): ?>
                                <?php $path = JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'mapGeoServicios'. DS . $this->shape[0]["nameArchivo"]; ?>
                                <tr>
                                    <!-- Nombre del Icono -->
                                    <td align="center">
                                        <?php echo $this->shape[0]["nameArchivo"]; ?>
                                    </td>

                                    <!-- DESCARGA de la imagen -->
                                    <td align="center">
                                        <a href="<?php echo $pathIcon; ?>">
                                            <?php echo JText::_('LB_DOWNLOAD'); ?>
                                        </a>
                                    </td>

                                    <!-- Eliminar el icono. -->
                                    <td align="center">
                                        <a href="#" class="elmIcon">
                                            <?php echo JText::_('LB_ELIMINAR'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                </fieldset>
            </div>

            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_ADMINMAPAS_INFOCAPAS_MAPAS'); ?>&nbsp;</legend>
                    <ul class="adminformlist">
                        <?php foreach( $this->form->getFieldset('urlsmapas') as $field ): ?>
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

            <?php if( $this->item->intCodigo_shp_ecorae != 0 ): ?>
                <div class="width-50 fltlft">
                    <fieldset class="adminform">
                        <a  class="modal" href="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo ($this->item->intCodigo_shp_ecorae == 0) ? 'default.png' : $this->item->intCodigo_shp_ecorae . ".png" ?>"> 
                            <img style="width: 200xp; 
                                 height: 250px" 
                                 src="<?php echo JURI::root() ?>images/stories/mapImages/<?php echo ($this->item->intCodigo_shp_ecorae == 0) ? 'default.png' : $this->item->intCodigo_shp_ecorae . ".png" ?>"
                                 >
                        </a>
                    </fieldset>
                <?php endif; ?>
            </div>

            <div>
                <input type="hidden" name="task" value="ecoraemapa.edit" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>