<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<!-- Lista de Unidades Territoriales Registradas -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_LSTUNDTERRITORIAL_TITLE' ) ?> </legend>
        <table id="lstUndTerritorialesInd" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_PROVINCIA_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_CANTON_LABEL' ) ?> </th>
                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_PARROQUIA_LABEL' ) ?> </th>                                
                    <th colspan="2" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_INDICADOR_OPERACION_FUENTE' ) ?> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<fieldset class="adminform">
    <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_LSTUNDTERRITORIAL_TITLE' ) ?> </legend>
    <ul class="adminformlist">
        <?php foreach( $this->form->getFieldset( 'unidadterritorial' ) as $field ): ?>
            <li>
                <?php echo $field->label; ?>
                <?php echo $field->input; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <div class="clr"></div>
    <div class="fltlft">
        <input id="btnAddUndTerritorial" type="button" value="<?php echo JText::_( 'COM_PROYECTOS_INDICADOR_ADDUNDTERRITORIAL_TITLE' ) ?>">
    </div>
    <div class="clr"></div>
</fieldset>