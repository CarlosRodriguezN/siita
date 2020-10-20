<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--Datos generales del tipo de sub programa-->
<div class="width-50 fltrt">
    <div id="imgTiposProgramas" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
    </div>
    <div id="formTiposProgramas" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_PROGRAMA_BTN_ADD_TIPOSUBPROGRAMA'); ?>&nbsp;</legend>
            <ul id="formTpoSubPrgPrg">
                <?php foreach ($this->form->getFieldset('tipoSubPrograma') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>

            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="AddTipoSubPrograma" type="button" value="<?php echo JText::_('GUARDAR_BTN'); ?>">
            <?php endIf; ?>
                
            <input id="cancelTipoSubPrograma" type="button" value="<?php echo JText::_('CANCELAR_BTN'); ?>">
            <input style="display: none" id="btnSaveChangesTipoSubPrograma" type="button" value="<?php echo JText::_('COM_PROGRAMA_BTS_SAVECHANGES_TIPOSUBPROGRAMA'); ?>">
        </fieldset>
    </div> 
</div> 
<!-- Sellecion del sub Programa-->
<div class="width-50 fltlft" >
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_('COM_PROGRAMA_TB_TIPOS_TBTIPOSUBPROGRMA'); ?>&nbsp;</legend>
        <div>
            <ul class="adminformlist">
                <li>
                    <label class="hasTip" title="<?php echo JText::_('COM_PROGRAMA_FIELD_TIPO_SUB_PROGRAMA_SUBPROGRAMA_DESC') ?>" aria-invalid="false"><?php echo JText::_('COM_PROGRAMA_FIELD_TIPO_SUB_PROGRAMA_SUBPROGRAMA_LABEL') ?></label>
                    <select id="cb_intId_SubPrograma">
                        <option value="0">SELECCIONE UN SUBPROGRAMA</option>  
                        <?php if ($this->subProgramas): ?>
                            <?php foreach ($this->subProgramas as $subPrograma): ?>
                                <option value="<?php echo $subPrograma->idSubPrograma ?>"><?php echo $subPrograma->alias ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </li>
            </ul>
        </div>
        <div class="fltrt">
            <input id="addTipoSubProgramaTable" type="button" value="<?php echo JText::_('BTN_AGREGAR_TSPRG'); ?>"> 
        </div>
        <table  id="tbtiposSubProgramas" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center">
                        <?php echo JText::_('COM_PROGRAMA_TB_LTS_CODIGOTSUBPRG_LBL'); ?>
                    </th>
                    <th align="center">
                        <?php echo JText::_('COM_PROGRAMA_TB_LTS_TIPOSUBDESCRIPCION_LBL'); ?>
                    </th>
                    <th colspan="2"  align="center"  width="15">
                        <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>