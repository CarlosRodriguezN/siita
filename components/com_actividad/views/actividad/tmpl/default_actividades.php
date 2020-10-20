<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div>
    <!--Lista de actividades-->
    <div class="width-50 fltlft" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_ACTIVIDAD_TAB_LISTA_ACTIVIDADES'); ?> </legend>
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <div class="fltrt">  
                    <input id="addActividadObjetivo" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR'); ?>&nbsp;">
                </div>  
            <?php endIf; ?>
            <table id="lstActividadesTable" class="tablesorter">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_('COM_ACTIVIDAD_TAB_ACTIVIDAD_TABLE_DESCRIPCION'); ?></th>
                        <th align="center"> <?php echo JText::_('COM_ACTIVIDAD_TAB_ACTIVIDAD_TABLE_TPO_GESTION'); ?></th>
                        <th align="center"> <?php echo JText::_('COM_ACTIVIDAD_TAB_ACTIVIDAD_TABLE_FCHACTIVIDAD'); ?></th>
                        <th align="center" colspan="3"> <?php echo JText::_('TL_ACCIONES'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <div id="tbSinReg" class="hide" align="center"><?php echo JText::_('COM_ACTIVIDAD_SIN_REGISTROS'); ?></div>
        </fieldset>
    </div>

    <div class="width-50 fltrt">
        
        <!--    Formulario con la data general de una Actividad -->
        <div id="editActividadObjetivoForm" class="hide">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_ACTIVIDAD_TAB_EDIT_ACTIVIDAD'); ?></legend>
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('actividad') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="clr"></div>
                <div class="clr"></div>
                
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <div>  
                        <input id="saveActiviadad" type="button" value="&nbsp;<?php echo JText::_('BTN_GUARDAR'); ?>&nbsp;">
                    </div> 
                <?php endIf; ?>

                <div>  
                    <input id="cancelActividad" type="button" value="&nbsp;<?php echo JText::_('BTN_CANCELAR'); ?>&nbsp;">
                </div> 
            </fieldset>
        </div>
        
        <!--    Gestion de archivos de respaldo de una actividad    -->
        <div id="editDocsActObjForm" class="hide">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_ACTIVIDAD_TAB_EDIT_ACTIVIDAD'); ?></legend>
                <ul class="adminformlist">
                    <li>
                        <table>
                            <tr>
                                <td><input type="file" id="uploadSon"></td>
                                <td><input id="cerrarlUpl" type="button" value="&nbsp;<?php echo JText::_('BTN_CERRAR'); ?>&nbsp;"></td>
                            </tr>
                        </table>               
                    </li>
                    <li>
                        <table id="lstDocActividades"class="tablesorter">
                            <thead>
                                <tr>
                                    <th align="center" > <?php echo JText::_('COM_ACTIVIDAD_TAB_EDIT_DOC_ACTIVIDAD'); ?></th>
                                    <th align="center" colspan="2"> <?php echo JText::_('TL_ACCIONES'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </li>
                </ul>
            </fieldset>
        </div>
    </div>

    <div id="imgActividadObjetivoForm" class="width-50 fltrt editbackground">

    </div>
    <div class="clr">
    </div>

</div>