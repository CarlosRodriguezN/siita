<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Lista de Variables Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_LISTA_ELEMENTOS') ?> </legend>

        <div class="clr"></div>
        <div class="fltrt">
            <input id="btnVariableNueva" type="button" value="<?php echo JText::_('COM_PEI_FIELD_NUEVA_VARIABLE_TITLE') ?>">
        </div>
        <div class="clr"></div>

        <table id="lstVarIndicadores" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center" width="50px"> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_TIPO_ELEMENTO_LABLE') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_ELEMENTO_LABLE') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_OPERACION') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_ELEMENTOS') ?> </legend>

        <!-- Formulario de Gestion de Indicadores -->
        <div>
            <fieldset class="adminform">
                <legend> <div id="lblElemento"> <?php echo JText::_('COM_PEI_FIELD_ATRIBUTO_INDICADOR') ?> </div> </legend>
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
                    <legend> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_UGESTION' ) ?> </legend>
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
                    <legend> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_RESPONSABLE' ) ?> </legend>
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
                    <legend> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_UGESTION' ) ?> </legend>
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
                    <legend> <?php echo JText::_( 'COM_PEI_FIELD_ATRIBUTO_RESPONSABLE' ) ?> </legend>
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
                <input id="btnAddVariable" type="button" value="<?php echo JText::_('COM_PEI_INDICADOR_ADDVARIABLE') ?>">
                <input id="btnCancelarVariable" type="button" value="<?php echo JText::_('COM_PEI_INDICADOR_CANCELAR') ?>">
            </div>
            <div class="clr"></div>
        </div>

        <div class="clr"></div>
    </fieldset>
</div>

<div class="clr"></div>