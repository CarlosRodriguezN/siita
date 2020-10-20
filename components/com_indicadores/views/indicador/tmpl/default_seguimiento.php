<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Valores de Seguimiento de una determinada variable -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_SEGUIMIENTO_VARIABLE_TITLE' ) ?> </legend>

        <div class="fltlft">
            <?php   
                $frmVariable = $this->form;
                $frmVariable->setFieldAttribute( 'idVariableIndicador', 'label', JText::_( 'COM_INDICADORES_SEG_VARIABLE_LABEL' ) );
                $frmVariable->setFieldAttribute( 'idVariableIndicador', 'description', JText::_( 'COM_INDICADORES_SEG_VARIABLE_DESC' ) );

                $lstVariables = $frmVariable->getField( 'idVariableIndicador' );
                
                echo $lstVariables->label . $lstVariables->input;
            ?>
        </div>
        <div class="clr"></div>
        
        <div class="fltrt">
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <input id="addSeguimientoTable" type="button" value=" &nbsp;<?php echo JText::_( 'COM_INDICADORES_FIELD_ADD_ATRIBUTO_SEGUIMIENTO' ) ?> &nbsp;">
            <?php endIf; ?>
        </div>
        
        <table id="lstSeguimiento" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_PLANIFICACION_FECHA_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_INDICADORES_FIELD_PLANIFICACION_VALOR_LABEL' ) ?> </th>
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_INDICADORES_OPCION' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>


<!-- Formulario de seleccion de variables asignadas a un determinado funcionario -->
<div class="width-50 fltrt">
    <div id="imgSeguimiento" class="editbackground"></div>
    <div id="frmSeguimiento" class="hide">

        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_INDICADORES_FIELD_SEGUIMIENTO_VARIABLE_TITLE' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'seguimientoVariable' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <div class="fltlft">
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddSeguimiento" type="button" value="<?php echo JText::_( 'COM_INDICADORES_ADD_SEGUIMIENTO' ) ?>">
                <?php endIf; ?>

                <input id="btnCancelSeguimiento" type="button" value="<?php echo JText::_( 'COM_INDICADORES_CANCEL_SEGUIMIENTO' ) ?>">
            </div>
            <div class="clr"></div>
        </fieldset>
        
    </div>
</div>

<div class="clr"></div>