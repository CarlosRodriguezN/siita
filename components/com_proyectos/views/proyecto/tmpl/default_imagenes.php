<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- ICONOS CARGADOS EN EL SISTEMA -->
<div class="m">
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_ICONO_TITLE' ) ?> </legend>
            <div>
                <div class="fltrt">
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <input id="iconoProyecto" type="file" multiple="true" class="fltrt">
                    <?php endIf; ?>
                </div>
                <div class="clr" > </div>
                    <table id="lstIconos" class="tablesorter width-100" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center" class="width-80"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_NOMBREIMG_TITLE' ) ?> </th>
                                <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_VISTA_TITLE' ) ?> </th>
                                <th align="center" class="width-10" > <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_DELIMAGEN_TITLE' ) ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if( count($this->icono) != 0 ): ?>
                                <?php $pathIcon = 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $this->intCodigo_pry . DS . 'icon' . DS . $this->icono[0]["nameArchivo"]; ?>
                                <tr>
                                    <!-- Nombre del Icono -->
                                    <td align="center">
                                        <?php echo $this->intCodigo_pry; ?>
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
                                            <?php echo JText::_( 'LB_DOWNLOAD' ); ?>
                                        </a>
                                    </td>

                                    <!-- Eliminar el icono. -->
                                    <td align="center">
                                        
                                        <?php if( $this->canDo->get( 'core.delete' ) ): ?>
                                            <a href="#" class="elmIcon">
                                                <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                        <?php endIf; ?>
                                        
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>
        </fieldset>
    </div>

    <!-- IMAGENES CARGADAS EN EL SISTEMA -->
    <!-- Iconos cargado en el sistema -->
    <div class="width-50 fltrt">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_IMAGENES_TITLE' ); ?> </legend>
            <div>
                <div class="fltrt">
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <input id="imgsProyecto" name="imgsProyecto" type="file" multiple="true">
                    <?php endIf; ?>
                </div>
                <div class="clr" > </div>
                <table id="lstImagenes" class="tablesorter width-100" cellspacing="1">
                    <thead>
                        <tr>
                            <th align="center" class="width-80"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_NOMBREIMG_TITLE' ) ?> </th>
                            <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_VISTA_TITLE' ) ?> </th>
                            <th align="center" class="width-10" colspan="2"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_IMAGEN_DELIMAGEN_TITLE' ) ?> </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if( $this->lstImagenes ): ?>
                            <?php foreach( $this->lstImagenes AS $imagen ): ?>
                                <?php $path = 'components' . DS . 'com_proyectos' . DS . 'images' . DS . $this->intCodigo_pry . DS . 'images' . DS; ?>
                                <tr id="<?php echo $imagen["regArchivo"]; ?>">

                                    <!--Nombre de la Imagen-->
                                    <td align="center"> 
                                        <?php echo $imagen["nameArchivo"] ?> 
                                    </td>

                                    <!-- Vista previa de la Imagen -->
                                    <td align="center">
                                        <a  href="<?php echo $path . $imagen["nameArchivo"] ?>" class="modal" > 
                                            <?php echo JText::_( 'LB_VER' ) ?>
                                        </a>
                                    </td>
                                    <!-- Descarga del archivo -->
                                    <td align="center">
                                        <a  href="<?php echo $path . $imagen["nameArchivo"] ?>"> 
                                            <?php echo JText::_( 'LB_DOWNLOAD' ) ?>
                                        </a>
                                    </td>

                                    <!-- Eliminar una Imagen -->
                                    <td align="center">

                                        <?php if( $this->canDo->get( 'core.delete' ) ): ?>
                                            <a href="#" class="elmImagen">
                                                <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                        <?php endIf; ?>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>