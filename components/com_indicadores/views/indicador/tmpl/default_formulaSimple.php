<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>



<!-- registro de formula -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_INDICADORES_FIELD_ATRIBUTO_ELEMENTO_FORMULA_LABLE') ?> </legend>
        <ul class="adminformlist">
            <!-- Formulario de asignacion de Formula a un indicador -->
            <div id="formulaIndicador">
                <table id="elementosFormula" width="100%" class="tablesorter" cellspacing="1">
                    <tbody>
                        <tr id="0">
                            <td> <a class='updVar'> <label id="jform_nombreNV-lbl" for="jform_nombreNV" class="hasTip" title="<?php echo JText::_('COM_INDICADORES_FIELD_NUMERADOR_TITLE'); ?>" style="min-width: 135px;"> <?php echo JText::_('COM_INDICADORES_FIELD_NUMERADOR_LABLE'); ?> </label> </a> </td>
                            <td> <textarea id="numerador" cols="3" rows="3" value="" style="width: 275px;"></textarea> </td>
                        </tr>

                        <tr id="1">
                            <td> <a class='updVar'> <label id="jform_nombreNV-lbl" for="jform_nombreNV" class="hasTip" title="<?php echo JText::_('COM_INDICADORES_FIELD_DENOMINADOR_TITLE'); ?>" style="min-width: 135px;"><?php echo JText::_('COM_INDICADORES_FIELD_DENOMINADOR_LABLE'); ?></label> </a> </td>
                            <td> <textarea id="denominador" cols="3" rows="3" value="" style="width: 275px;"></textarea> </td>
                        </tr>
                    </tbody>
                </table>                
            </div>
        </ul>
    </fieldset>
</div>

<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_INDICADORES_FIELD_ATRIBUTO_ELEMENTO') ?> </legend>

        <!-- Formulario de Gestion de Indicadores -->
        <div>
            <fieldset class="adminform">
                <legend> <div id="lblElemento"> <?php echo JText::_('COM_INDICADORES_FIELD_ATRIBUTO_VARIABLE') ?> </div> </legend>
                <ul class="adminformlist">
                    <!-- Formulario de asignacion de Indicadores a un indicador -->
                    <div id="frmIndicadorVar">
                        <?php foreach ($this->form->getFieldset('indicador') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </div>

                    <!-- Formulario de registro de nueva variable -->
                    <div id="nuevaVariable">
                        <?php foreach ($this->form->getFieldset('nuevaVariable') as $field): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endforeach; ?>
                    </div>
                </ul>
            </fieldset>
        </div>

        <div class="clr"></div>
        
        <!-- Formulario Responsables - Nueva Variable -->
        <div id="responsablesIndicador">
            <div>
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UGESTION' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmUGResponableVariable">
                            <?php foreach( $this->form->getFieldset( 'indUGResponsable' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </div>
                    </ul>
                </fieldset>
            </div>
            <div class="clr"></div>

            <!-- Formulario de Gestion Funcionarios Responsables -->
            <div>
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RESPONSABLE' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmFuncionarioResponableVariable">
                            <?php foreach( $this->form->getFieldset( 'indFuncionarioUG' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>

                        </div>
                    </ul>
                </fieldset>
            </div>
        </div>
        
        <!-- Formulario Responsables - Nueva Variable -->
        <div id="responsableNV">
            <div>
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_UGESTION' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmUGResponableVariable">
                            <?php foreach( $this->form->getFieldset( 'VarUGResponsable' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </div>
                    </ul>
                </fieldset>
            </div>
            <div class="clr"></div>
            
            <!-- Formulario de Gestion Funcionarios Responsables -->
            <div>
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_RESPONSABLE' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmFuncionarioResponableVariable">
                            <?php foreach( $this->form->getFieldset( 'varFunResponsable' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>

                        </div>
                    </ul>
                </fieldset>
            </div>
        </div>
        
        <div class="clr"></div>
        <div class="width-100 fltrt">
            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddVariable" type="button" value="<?php echo JText::_('COM_INDICADORES_ADD') ?>">
                <input id="btnCancelarVariable" type="button" value="<?php echo JText::_('COM_INDICADORES_CANCEL') ?>">
            </div>
            <div class="clr"></div>
        </div>

        <div class="clr"></div>
    </fieldset>
</div>

<div class="clr"></div>