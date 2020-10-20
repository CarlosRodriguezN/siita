<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--lISTA DE PRORROGAS-->
<div class="width-50 fltrt">
    <div id="ieavProrroga" class="editbackground"></div>
    <div id="editProrrogaForm" class="hide">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS' ); ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'prorroga' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr">
            </div>
            <input id="addProrroga" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
            <input id="cancelarProrroga" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
        </fieldset>
    </div>
</div>
<div class="width-50">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS' ); ?>&nbsp;</legend>
        <div class="fltrt">
            <input id="addProrrogaTable" type="button" value="<?php echo JText::_( 'BTN_AGREGAR' ); ?>">
        </div>
        <ul>
            <li>
                <table  id="tbProrrogaContrato" class="tablesorter" cellspacing="1" >
                    <thead>
                        <tr>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_CODIGO' ); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_MORA' ); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_PLAZO' ); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_DOCUMENTO' ); ?>
                            </th>
                            <th align="center" width="15%">
                                <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PRORROGAS_OBSERVACION' ); ?>
                            </th>
                            <th  colspan="2" align="center" width="15">
                                <?php echo JText::_( 'ANY_ACCION_TABLA' ); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $this->prorrogas AS $key => $prorroga ): ?>
                            <tr id="<?php echo $key + 1 ?>">
                                <td >
                                    <?php echo $prorroga->idCodigoProrroga ?>
                                </td>
                                <td >
                                    <?php echo $prorroga->mora ?>
                                </td>
                                <td >
                                    <?php echo $prorroga->plazo ?>
                                </td>
                                <td >
                                    <?php echo $prorroga->documento ?>
                                </td>
                                <td >
                                    <?php echo $prorroga->observacion ?>
                                </td>
                                <td style="width: 15px">
                                    <a class="editProrroga">
                                        <?php echo JText::_( 'LB_EDITAR' ); ?>
                                    </a>
                                </td>
                                <td  style="width: 15px">
                                    <a class="delProrroga">
                                        <?php echo JText::_( 'LB_ELIMINAR' ); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </li>
        </ul>
    </fieldset>
</div>