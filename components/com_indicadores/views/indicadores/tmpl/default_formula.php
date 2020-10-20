<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Creacion de la formula -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_ELEMENTO_FORMULA_LABLE' ) ?> </legend>
        <ul class="adminformlist">
            <!-- Formulario de asignacion de Formula a un indicador -->
            <div id="formulaIndicador">
                <li>
                    <textarea id="formulaDescripcion" cols="3" rows="3" value="" style="width: 380px;"></textarea>
                </li>
            </div>
        </ul>

        <div class="clr"></div>
        <div>
            <table id="lstElemtosCalculadora" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_TIPO_ELEMENTO_LABLE' ) ?> </th>
                        <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPERACION' ) ?> </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <input id="btnFrmSuma" type="button" value="<?php echo JText::_( 'COM_INDICADORES_CALCULADORA_SUMA_TITLE' ) ?>">
                            <input id="btnFrmResta" type="button" value="<?php echo JText::_( 'COM_INDICADORES_CALCULADORA_RESTA_TITLE' ) ?>">
                            <input id="btnFrmMultiplicacion" type="button" value="<?php echo JText::_( 'COM_INDICADORES_CALCULADORA_MULTIPLICACION_TITLE' ) ?>">
                            <input id="btnFrmDivision" type="button" value="<?php echo JText::_( 'COM_INDICADORES_CALCULADORA_DIVISION_TITLE' ) ?>">
                        </td>
                        <td> 
                            <input id="btnLimpiarFormula" type="button" value="<?php echo JText::_( 'COM_INDICADORES_LIMPIAR' ) ?>">
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="clr"></div>

    </fieldset>
</div>

<div class="width-50 fltrt">

    <div id="imgVariables" class="editbackground"></div>
    <div id="frmVariables" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_ELEMENTO' ) ?> </legend>

            <!-- Formulario de Gestion de Indicadores -->
            <div>
                <fieldset class="adminform">
                    <legend id="lblElemento"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_VARIABLE' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Formulario de asignacion de un Indicador como variable -->
                        <div id="frmIndicadorVar">
                            <?php foreach( $this->form->getFieldset( 'indicadorVariable' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </div>

                        <!-- Formulario de registro de nueva variable -->
                        <div id="frmNuevaVariable">
                            <?php foreach( $this->form->getFieldset( 'nuevaVariable' ) as $field ): ?>
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
                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                        <input id="btnAddVariable" type="button" value="<?php echo JText::_( 'COM_INDICADORES_FIELD_GUARDAR_FORMULA_TITLE' ) ?>">
                    <?php endIf; ?>

                    <input id="btnCancelarVariable" type="button" value="<?php echo JText::_( 'COM_INDICADORES_FIELD_CANCELAR_FORMULA_TITLE' ) ?>">
                </div>
                <div class="clr"></div>
            </div>

            <div class="clr"></div>
        </fieldset>

    </div>
</div>


<!-- Lista de Variables Registradas -->
<div class="width-50 fltrt">
    <fieldset class="adminform" style="padding-left: 15px; padding-right: 15px;">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_LISTA_ELEMENTOS' ) ?> </legend>

        <div class="clr"></div>
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="btnVariableNueva" type="button" value="<?php echo JText::_( 'COM_INDICADORES_FIELD_NUEVA_VARIABLE_TITLE' ) ?>">
                <input id="btnIndicadorVariable" type="button" value="<?php echo JText::_( 'COM_INDICADORES_FIELD_INDICADOR_VARIABLE_TITLE' ) ?>">
            <?php endIf; ?>
        </div>
        <div class="clr"></div>

        <table id="lstVarIndicadores" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center" width="50px"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_TIPO_ELEMENTO_LABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_ATRIBUTO_ELEMENTO' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="clr"></div>