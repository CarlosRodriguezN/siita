<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="width-100" >
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GENERAL' ); ?>&nbsp;</legend>
        <div class="width-50 fltlft">
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'general' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="width-50 fltrt">
            <ul>
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_PLAZO' ); ?>&nbsp;</legend>
                    <?php foreach( $this->form->getFieldset( 'plazoContrato' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </fieldset> 
            </ul>
            <ul>
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_DOCUMENTO' ); ?>&nbsp;</legend>
                    <?php foreach( $this->form->getFieldset( 'docContrato' ) as $field ): ?>
                        <li>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <table id="docsTable" class="tablesorter" cellspacing="1">
                            <thead >
                                <tr>
                                    <th align="center">
                                        <?php echo JText::_( 'COM_CONTRATOS_TAB_LABEL_DOCUMENTO' ); ?>
                                    </th>
                                    <th  align="center" colspan="2">
                                        <?php echo JText::_( 'TL_ACCIONES' ); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if( count( $this->lstDocsContrato ) > 0 ): ?>
                                    <?php $idContrato = JRequest::getVar( 'intIdContrato_ctr' ); ?>
                                    <?php $path = "media" . DS . "ecorae" . DS . "docs" . DS . 'contratos' . DS . $idContrato . DS; ?>
                                    <?php foreach( $this->lstDocsContrato AS $doc ): ?>
                                        <tr id="<?php echo $doc['regArchivo'] ?>">
                                            <td align="center">
                                                <?php echo $doc['nameArchivo'] ?>
                                            </td>
                                            <td align="center" width="15">
                                                <a href="<?php echo $path . $doc['nameArchivo'] ?>">
                                                    <?php echo JText::_( 'LB_DOWNLOAD' ) ?>
                                            </td>
                                            <td align="center" width="15">
                                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                    <a href="#" class="deleteDoc">
                                                        <?php echo JText::_( 'LB_ELIMINAR' ) ?>
                                                    </a>
                                                <?php else: ?>
                                                        <?php echo JText::_( 'LB_ELIMINAR' ) ?>
                                                <?php endIf; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </li>
                </fieldset> 
            </ul>
        </div>
    </fieldset>
</div>
