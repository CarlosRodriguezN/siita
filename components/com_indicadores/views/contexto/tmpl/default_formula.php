<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Metodo de Calculo -->
<div class="width-50 fltlft">
    <div>
        <fieldset class="adminform">
            <legend> <div id="lblElemento"> <?php echo JText::_('COM_INDICADORES_CONTEXTO_METODO_CALCULO') ?> </div> </legend>
            <ul class="adminformlist">
                <!-- Formulario de asignacion de Indicadores a un indicador -->
                <div id="frmIndicadorVar">
                    <?php foreach ($this->form->getFieldset('metodocalculo') as $field): ?>
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

<!-- formulario de registro de indicadores -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ATRIBUTO_ELEMENTO') ?> </legend>

        <!-- Formulario de Gestion de Indicadores -->
        <div>
            <fieldset class="adminform">
                <legend> <div id="lblElemento"> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ATRIBUTO_INDICADOR') ?> </div> </legend>
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
                </ul>
            </fieldset>
        </div>

        <div class="clr"></div>

        <!-- Formulario Responsables - Nueva Variable -->
        <div id="responsablesIndicador">
            <div>
                <fieldset class="adminform">
                    <legend> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ATRIBUTO_UGESTION') ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmUGResponableVariable">
                            <?php foreach ($this->form->getFieldset('indUGResponsable') as $field): ?>
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
                    <legend> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ATRIBUTO_RESPONSABLE') ?> </legend>
                    <ul class="adminformlist">
                        <!-- Responsable de las variables -->
                        <div id="frmFuncionarioResponableVariable">
                            <?php foreach ($this->form->getFieldset('indFuncionarioUG') as $field): ?>
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
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddElementoContexto" type="button" value="<?php echo JText::_('COM_INDICADORES_FIELD_GUARDAR_FORMULA_TITLE') ?>">
                <?php endIf; ?>
                
                <input id="btnCancelarContexto" type="button" value="<?php echo JText::_('COM_INDICADORES_FIELD_CANCELAR_FORMULA_TITLE') ?>">
            </div>
            <div class="clr"></div>
        </div>

        <div class="clr"></div>
    </fieldset>
</div>

<!-- Lista de Elementos que forman un contexto -->
<div class="width-50 fltrt">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ATRIBUTO_ELEMENTO') ?> </legend>
        <div class="clr"></div>
        
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addElementoContexto" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_ELEMENTO_FORMULA_TITLE' ) ?> &nbsp;">
            <?php endIf; ?>
        </div>
        
        <div class="clr"></div>
        <table id="lstVarIndicadores" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_('COM_INDICADORES_CONTEXTO_ELEMENTO') ?> </th>
                    <th align="center"> <?php echo JText::_('COM_INDICADORES_CONTEXTO_FACTOR_PONDERACION') ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_('COM_INDICADORES_OPERACION') ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="clr"></div>