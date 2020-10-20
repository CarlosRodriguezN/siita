<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Unidades Territoriales Registradas por el usuario -->
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LSTUNDTERRITORIAL_TITLE') ?> </legend>
        
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addUnidadTerritorialTable" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>">
            </div>
        <?php endif; ?>
        
        <table id="lstUndTerritoriales" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_PROVINCIA_LABEL') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_CANTON_LABEL') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_PARROQUIA_LABEL') ?> </th>                                
                    <th colspan="2" align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->undTerritorial): ?>
                    <?php foreach ($this->undTerritorial as $key => $utRow): ?>
                        <tr id="<?php $idReg = ++$key; ?>">
                            <td align="center"><?php echo $utRow->provincia ?></td>
                            <td align="center"><?php
                                if ($utRow->idCanton) {
                                    echo $utRow->canton;
                                } else {
                                    echo '---';
                                }
                                ?></td>
                            <td align="center"><?php
                                if ($utRow->idParroquia) {
                                    echo $utRow->parroquia;
                                } else {
                                    echo '---';
                                }
                                ?></td>
                            
                            <?php if( $this->canDo->get( 'core.edit' ) ): ?>
                                <td align="center" width="15" ><a id = "updUT-<?php echo $idReg ?>" class="updUndTerritorial">Editar</a></td>
                                <td align="center" width="15" ><a id = "delUT-<?php echo $idReg ?>" class="delUndTerritorial">Eliminar</a></td>
                            <?php else: ?>                                
                                <td align="center" width="15" >Editar</td>
                                <td align="center" width="15" >Eliminar</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?> 
                    <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Formulario  para registrar Unidades Territoriales -->
<div class="width-40 fltrt" >
    <div id="imgUnidadTerritorial" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
    </div>
    <div id="frmUnidadTerritorial" class ="hide">
        <fieldset class="adminform">
            <legend> Datos Ubicación Territorial </legend>
            <form id="frmDtaUnidadTerritorial" >
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('ubicacionTerritorialCP') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="clr"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddUndTerritorial" type="button" value="<?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_ADDUNDTERRITORIAL_TITLE') ?>">
                <?php endIf; ?>

                <input id="btnLimpiarUndTerritorial" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
                <div class="clr"></div>
            </form>
        </fieldset>
    </div>
    
</div>
