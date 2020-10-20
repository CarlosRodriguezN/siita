<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="width-100 fltlft" >
    <div class="width-30 fltlft">
            <!-- Gestiona los POA's de una Unidad de Gestion -->
            <div class='m'>
                <div id="lstPoas">
                    <div class="fltrt"> 
                        <a href= "index.php?option=com_funcionarios&view=poa&layout=edit&id=0&op=0&padre=0&idEntidad=<?php echo $this->item->intIdentidad_ent ?> &tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x:700, y:400}}">
                            <input id="newPoaUG" type="button" value=" &nbsp;<?php echo JText::_('BTN_NUEVO_PLAN') ?> &nbsp;">
                        </a>    
                    </div> 
                    <?php if( !empty( $this->lstPoasUG ) ): ?>
                    <div id="tbPoas">
                    <?php else: ?>
                    <div id="tbPoas" class="hide">
                    <?php endif; ?>
                        <table id="tbLstPoas" class="adminlist">
                            <thead> 
                                <tr>
                                    <th align="center" > <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?> </th>
                                    <th align="center" > <?php echo JHtml::_('grid.sort', 'COM_UNIDAD_GESTION_FIELD_UG_NOMBRE_LABEL', 'descripcionPoa', $listDirn, $listOrder); ?> </th>
                                    <th colspan="2" align="center"> <?php echo JHtml::_('grid.sort', 'ACCIONES', 'descripcionPoa', $listDirn, $listOrder); ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <?php if( empty( $this->lstPoasUG )  ): ?>
                    <div id="srPoas" align="center"> <p> <?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?> </p> </div>
                    <?php endif; ?>
                    <!-- Id de registro del POA para Gestion -->
                    <input id="idRegPoa" type="hidden" name="idRegPoa"  value="<?php echo $regPoa; ?>" />
                </div>
            </div>
    </div>
        
    <!-- Gestiona los Objetivos de un Poa de una Unidad de Gestion -->
    <div class="width-70 fltrt">
        <fieldset class="adminform">
            <legend> <div id="idLstPOAs"> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVOS_UG_LABEL') ?> </div> </legend>
            <!-- Lista de Objetivos de un Poa de una Unidad de Gestion -->
            <div class="width-55 fltlft">
                <?php if( $this->canDo->get( 'core.create' ) ): ?>
                    <div class="fltrt"> 
                        <input id="poaObjs" class="addObjetivoPoa" type="button" value=" &nbsp;<?php echo JText::_('BTN_AGREGAR_OBJ') ?> &nbsp;">
                    </div> 
                <?php endIf; ?>

                <table id="tbLstObjetivos" width="100%" class="tablesorter" cellspacing="1">
                    <thead>
                        <tr>
                            <th align="center"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_DESCRIPCION_LABEL') ?></th>
                            <th align="center"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_PRIORIDAD_LABEL') ?></th>
                            <th colspan="3" align="center"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_COMPLEMENTO') ?></th>
                            <th colspan="2" align="center"><?php echo JText::_('ACCIONES') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <div id="srObj" align="center" class="hide"> <p> <?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?> </p> </div>
            </div>

            <!--    Fomulario para registrar un nuevo objetivo del UNIDAD_GESTION-->
            <div class="width-45 fltrt" >
                <div id="imgObjetivo" class="editbackground" >

                </div>
                <div id="frmObjetivo" class ="hide" >
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_DATA_GENERAL_LABEL') ?> </legend>
                        <ul class="adminformlist">
                            <li>
                                <input type="hidden" name="jform[intId_ob]" id="jform_intId_ob" value="" />
                            </li>
                            <li>
                                <!-- 2 Id de tipo de Objetivo Operativo Anual POA-->
                                <input type="hidden" name="jform[intId_tpoObj]" id="jform_intId_tpoObj" value="2" />
                            </li>
                            <li>
                                <label id="jform_intPrioridad_ob-lbl" for="jform_intPrioridad_ob" class="hasTip required" title="Prioridad::Prioridad del objetivo"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_PRIORIDAD_LABEL') ?><span class="star">&#160;*</span></label>
                                <select id="jform_intPrioridad_ob" name="jform[intPrioridad_ob]">   <option value="0"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_PRIORIDAD_TITLE') ?></option>
                                    <option value="1" >ALTA</option>
                                    <option value="2" >MEDIA</option>
                                    <option value="3" >BAJA</option></select>
                            </li>
                            <li>
                                <label id="jform_strDescripcion_ob-lbl" for="jform_strDescripcion_ob" class="hasTip required" title="Descripción::Nombre del objetivo"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_DESCRIPCION_LABEL') ?><span class="star">&#160;*</span></label>
                                <textarea name="jform[strDescripcion_ob]" id="jform_strDescripcion_ob" cols="4" rows="3" class="inputbox required"></textarea>
                            </li>
                        </ul>
                        <div id="padre" class="hide">
                            <input type="file" id="uploadFather" >
                        </div>
                        <div class="clr"></div>
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input class="btnAddObj" id="btnAddObj" type="button" value=" &nbsp;<?php echo JText::_('BTN_GUARDAR') ?> &nbsp;">
                        <?php endIf; ?>

                        <input class="btnCancel" id="btnCancel" type="button" value=" &nbsp;<?php echo JText::_('BTN_CANCELAR') ?> &nbsp;">
                        <div class="clr"></div>
                    </fieldset>
                </div>
            </div>
        </fieldset>
    </div>
</div>