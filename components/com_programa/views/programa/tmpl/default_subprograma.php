<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--Lista de sub Programas -->
<div class="width-50 fltrt">
    <div id="imgSubPrograma" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
    </div>
    <div id="formSubPrograma" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_('COM_PROGRAMA_ADDSUBPRORAMA_LB_PROGRAMA'); ?>&nbsp;</legend>
            <ul class="adminformlist" id="frmSubPrgPrg">
                <?php foreach ($this->form->getFieldset('subPrograma') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul  >
            <div class="clr"></div>
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addSubPrograma" type="button" value="<?php echo JText::_('GUARDAR_BTN'); ?>">
            <?php endIf; ?>

            <input id="cancelarSubPrograma" type="button" value="<?php echo JText::_('CANCELAR_BTN'); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_PROGRAMA_LTS PROGRAMAS'); ?></legend>
        <div class="fltrt">
            <input id="addSubPorgramaTable" type="button" value="<?php echo JText::_('BTN_AGREGAR_SPRG'); ?>">
        </div>
        <table  id="tbSubProgramas" class="tablesorter" cellspacing="1" >
            <thead>
                <tr>
                    <th align="center" width="15%">
                        <?php echo JText::_('COM_PROGRAMA_TB_LTS_CODIGO_LBL'); ?>
                    </th>
                    <th align="center">
                        <?php echo JText::_('COM_PROGRAMA_TB_LTS_ALIAS_LBL'); ?>
                    </th>
                    <th align="center">
                        <?php echo JText::_('COM_PROGRAMA_TB_LTS_DESCRIPCION_LBL'); ?>
                    </th>
                    <th  colspan="2" align="center" width="15">
                        <?php echo JText::_('ANY_ACCION_TABLA'); ?>
                    </th>
                </tr>
            </thead>
            <tbody id="tbBodySubPrograma">
                <?php if ($this->subProgramas): ?>
                    <?php foreach ($this->subProgramas as $key => $subPrograma): ?>
                        <tr id="<?php echo $key + 1 ?>" class="trSubPrograma">
                            <td><?php echo $subPrograma->codigoSubPrograma ?></td>
                            <td><?php echo $subPrograma->alias ?></td>
                            <td><?php echo $subPrograma->descripcion ?></td>
                            <td width="15" ><a class="editSubPrograma">
                                    <?php echo JText::_('ANY_ACCION_TABLA_EDIT'); ?>
                                </a>
                            </td>
                            <td width="15" ><a class="delSubPrograma">
                                    <?php echo JText::_('ANY_ACCION_TABLA_DEL'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>
