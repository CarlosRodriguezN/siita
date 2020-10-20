<?php
// No direct access to this file
// TAB ESTADO GARANTIA lenguajes
defined('_JEXEC') or die('Restricted Access');
?>

<!--Lista-->
<div id="contratistaTab" class="width-100 fltlft">
    <div class="width-100 fltleft">
        <ul>
            <li><a href="#contratista"><?php echo JText::_('COM_CONTRATOS_TAB_CONTRATISTA'); ?></a></li>
            <li><a href="#contactosContratista"><?php echo JText::_('COM_CONTRATOS_TAB_CONTACTOS'); ?></a></li>
        </ul>
        <div id="contratista">
            <div class="width-50 fltrt">
                <div id="imagenEcuAmaVida" class="editbackground"></div>
                <div id="editContratista" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_CONTRATISTA_CONTRATISTAS'); ?>&nbsp;</legend>
                        <ul class="adminformlist" id="formContratistaCnt">
                            <?php foreach ($this->form->getFieldset('contratista') as $field): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                            <div class="clr"></div>
                            
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <input id="addContratista" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                            <?php endIf ?>

                            <input id="cancelarContratista" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="width-50">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_CONTRATISTA_LISTACONTRATISTAS'); ?>&nbsp;</legend>
                    
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <div class="fltrt">  
                            <input id="addContratistaTable" type="button" value="<?php echo JText::_('BTN_AGREGAR_CNTRTS'); ?>">
                        </div>
                    <?php endIf; ?>

                    <table id="tbLtsContratistas" class="tablesorter" cellspacing="1" >
                        <thead>
                            <tr>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_CONTRATISTA'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_OBSERVACION'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_FECHAINICIO'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_FECHAFIN'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_FECHAREGIS'); ?>
                                </th>
                                <th  colspan="2" align="center" width="15">
                                    <?php echo JText::_('TL_ACCIONES'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($this->contratistas): ?>
                                <?php foreach ($this->contratistas AS $contratista): ?>
                                    <tr id="<?php echo $contratista->idContratistaContrato ?>">
                                        <td>
                                            <?php echo $contratista->strContratista ?>
                                        </td>
                                        <td>
                                            <?php echo $contratista->observacion ?>
                                        </td>
                                        <td>
                                            <?php echo $contratista->fechaInicio ?>
                                        </td>
                                        <td>
                                            <?php echo $contratista->fechaFin ?>
                                        </td>
                                        <td>
                                            <?php echo $contratista->fechaRegistro ?>
                                        </td>
                                        <td style="width: 15px">
                                            
                                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                <a class="editContratista">
                                                    <?php echo JText::_('LB_EDITAR'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo JText::_('LB_EDITAR'); ?>
                                            <?php endIf; ?>
                                            
                                        </td>
                                        <td  style="width: 15px">
                                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                <a class="delContratista">
                                                    <?php echo JText::_('LB_ELIMINAR'); ?>
                                                </a>
                                            <?php else:?>
                                                    <?php echo JText::_('LB_ELIMINAR'); ?>
                                            <?php endIf;?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
        <!--Contactos Contratista-->
        <div id="contactosContratista">
            <div class="width-50 fltrt">
                <div id="imgContactoContratistaForm" style=" 
                     background: url(images/logo_default.jpg);
                     background-size: contain;
                     background-repeat: no-repeat;
                     background-position: center center;
                     width: 100%;
                     height: 213px;"
                     >
                </div>
                <div id="editContactoContratistaForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_CONTRATISTA_CONTACTO'); ?>&nbsp;</legend>
                        <ul class="adminformlist" id="formContactosCnt">
                            <?php foreach ($this->form->getFieldset('contacto') as $field): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr">
                        </div>
                        <div>
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <input id="addContratistaContacto" type="button" value="<?php echo JText::_('BTN_GUARDAR'); ?>">
                            <?php endIf; ?>

                            <input id="cancelContratistaContacto" type="button" value="<?php echo JText::_('BTN_CANCELAR'); ?>">
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="width-50">
                <fieldset class="adminform width-50">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_TAB_CONTRATISTA_LSTCONTACTOS'); ?>&nbsp;</legend>
                    <div class="fltrt">
                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                            <input id="addTableContratistaContacto" type="button" value="<?php echo JText::_('BTN_AGREGAR_CNT_CNT'); ?>">
                        <?php endIf;?>
                    </div>
                    <div class="fltleft">
                        <?php foreach ($this->form->getFieldset('contratistaFiltro') as $field): ?>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        <?php endforeach; ?>
                    </div>

                    <table  id="tbLtsContacto" class="tablesorter" cellspacing="1" >
                        <thead>
                            <tr>
                                <th align="center" >
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_CARGO'); ?>
                                </th>
                                <th align="center" >
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_CEDULA'); ?>
                                </th>
                                <th align="center" >
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_NOMBREAPELLIDOS'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_CELULARTELEFONO'); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_('COM_CONTRATOS_TAB_GENERAL_CONTRATISTA_CORREOELECTRONICO'); ?>
                                </th>
                                <th  colspan="2" align="center" >
                                    <?php echo JText::_('TL_ACCIONES'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div> 
</div>

