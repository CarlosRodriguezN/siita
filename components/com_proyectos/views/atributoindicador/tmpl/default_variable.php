<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Variables Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_LSTVARIABLES' ) ?> </legend>

        <div class="clr"></div>
        <div class="fltrt">
            <input id="btnNuevaVariable" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_FIELD_NUEVA_VARIABLE_TITLE' ) ?>">
        </div>
        <div class="clr"></div>
        
        <table id="lstVarIndicadores" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VARIABLE' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VARIABLE_UNDMEDIDA' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VARIABLE_UNDANALISIS' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_OPERACION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!--Formulario de Gestion de Variables -->
<fieldset class="adminform">
    <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_ATRIBUTO_VARIABLE' ) ?> </legend>
    <ul class="adminformlist">
        <!-- Formulario de asignacion de variables a un indicador -->
        <div id="frmVariable">
            <?php foreach( $this->form->getFieldset( 'variable' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
            <?php endforeach; ?>
            
            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddVariable" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_ADDVARIABLE' ) ?>">
                <input id="btnCancelarVariable" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_CANCELAR' ) ?>">
            </div>
            <div class="clr"></div>
            
        </div>

        <!-- Formulario de registro de nueva variable -->
        <div id="nuevaVariable">
            <?php foreach( $this->form->getFieldset( 'nuevaVariable' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
            <?php endforeach; ?>
            
            <div class="clr"></div>
            <div class="fltlft">
                <input id="btnAddVariable" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_ADDVARIABLE' ) ?>">
                <input id="btnCancelarVariable" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_CANCELAR' ) ?>">
            </div>
            <div class="clr"></div>
        </div>

    </ul>

    
</fieldset>