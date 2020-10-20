<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHTML::_('behavior.formvalidation');
?>

<div class="width-50 fltlft" >
    <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_FUNCIONARIOS_FIELD_DATA_GENERAL_LABEL' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('funcionario') as $field): ?>
                    <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
                
                <?php if($this->item->intCodigo_fnc == null): ?>
                    <?php foreach ($this->form->getFieldset('password') as $field): ?>
                    <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="clr"></div>
            <?php echo $this->form->getLabel('descripcion'); ?>
            <div class="clr"></div>
            <?php echo $this->form->getInput('descripcion'); ?>
            
            <?php if($this->item->intCodigo_fnc != null): ?>
                <div style="padding-top: 5px"> 
                    <a href= "index.php?option=com_funcionarios&view=password&layout=edit&idUsrFnc=<?php echo $this->item->intIdUser_fnc ?> &tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x:600, y:300}}">
                        <?php echo JText::_('BTN_CAMBIAR_PASS') ?>    
                    </a>
                </div>
            <?php endif; ?>
        </fieldset>
</div>

<div class="width-50 fltrt" >
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_FUNCIONARIOS_FIELD_DTA_UNIDAD_GESTION_LABEL' ) ?> </legend>
        <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset('unidadgestion') as $field): ?>
                <li>
                    <?php echo $field->label; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="clr"></div>
        <fieldset class="adminform" style="padding: 10px">
            <legend style="font-size: small; font-weight: normal;"> <?php echo JText::_( 'COM_FUNCIONARIOS_FIELD_UG_OP_ADD_TITLE' ) ?> </legend>
            <table id="cheksFncOpsAdds" >
                <thead>
                    <tr><th></th></tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </fieldset>
        <div class="clr"></div>
        <?php echo $this->form->getLabel('descripcion'); ?>
        <div class="clr"></div>
        <?php echo $this->form->getInput('descripcion'); ?>
    </fieldset>
</div>
