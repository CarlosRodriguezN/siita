<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div id="garantiasTab" class="width-100 fltlft">
    <div class="width-100 fltleft">
        <ul>
            <li><a href="#garantias"><?php echo JText::_( 'COM_CONTRATOS_TAB_GARANTIAS_GARANTIAS' ); ?></a></li>
            <li><a href="#estados"><?php echo JText::_( 'COM_CONTRATOS_TAB_GARANTIAS_GARANTIASESTADOS' ); ?></a></li>
        </ul>

        <!--Editar las garantias-->
        <div id="garantias">
            <div class="width-50 fltrt">
                <div id='imgGarantiaForm'  class="editbackground"></div>
                <div id="editGarantiaForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GARANTIAS' ); ?>&nbsp;</legend>
                        <ul class="adminformlist" id="formGarantiaCnt">
                            <?php foreach( $this->form->getFieldset( 'garantia' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>
                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addGarantia" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
                        <?php endIf; ?>
                            
                        <input id="cancelGarantia" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
                    </fieldset>
                </div>
            </div>
            
            <!--Listado de las garantias-->
            <div class="width-50 fltleft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAS' ); ?>&nbsp;</legend>
                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                        <div class="fltrt">
                            <input id="addGarantiasTable" type="button" value="&nbsp;<?php echo JText::_('BTN_AGREGAR_GRA'); ?>&nbsp;">
                        </div>
                    <?php endIf; ?>
                    <table id="tbLstGarantias" class="tablesorter" cellspacing="1" >
                        <thead>
                            <tr>
                                <th align="center" >
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIA_CODIGO' ); ?>
                                </th>
                                <th align="center" >
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIA_MONTO' ); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIA_DESDE' ); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIA_HASTA' ); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIA_ESTADO' ); ?>
                                </th>
                                <th  colspan="2" align="center">
                                    <?php echo JText::_( 'TL_ACCIONES' ); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                             
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
        <div id="estados">
            <!--ESTADOS-->
            <div  class="width-50 fltrt">
                <div id='imgEstGarantiaForm' style="background: url(images/logo_default.jpg);
                     background-size: contain;
                     background-repeat: no-repeat;
                     background-position: center center;
                     width: 100%;
                     height: 213px;"></div>
                <div id="editEstGarantiaForm" class="hide">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAS_ESTADO' ); ?>&nbsp;</legend>
                        <ul class="adminformlist" id="frmEtdGrtCnt" >
                            <?php foreach( $this->form->getFieldset( 'estadoGarantia' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clr"></div>

                        <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                            <input id="addGarantiaEstado" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ); ?>">
                        <?php endIf; ?>

                        <input id="cancelGarantiaEstado" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' ); ?>">
                    </fieldset>
                </div>
            </div>
            <!--Lista de los estados de las garantias-->
            <div>
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAS_ESTADOS' ); ?>&nbsp;</legend> 
                    <div>
                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                            <div class="fltrt">
                                <input id="addGarantiaEstadoTable" type="button" value="<?php echo JText::_( 'BTN_AGREGAR_STD_GRA' ); ?>">
                            </div>
                        <?php endIf; ?>

                        <div>
                            <ul>
                                <?php foreach( $this->form->getFieldset( 'estadoGarantiaGarantia' ) as $field ): ?>
                                    <li>
                                        <?php echo $field->label; ?>
                                        <?php echo $field->input; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <table id="tbLstGarantiasEstados" class="tablesorter" cellspacing="1" >
                        <thead>
                            <tr class="trGarantiaEstado">
                                <th align="center">
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAESTADOS_ESTADO' ); ?>
                                </th>
                                <th align="center" >
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAESTADOS_OBSERVACION' ); ?>
                                </th>
                                <th align="center">
                                    <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL_GARANTIAESTADOSA_FECHAMODIFICACION' ); ?>
                                </th>

                                <th  colspan="2" align="center" width="15">
                                    <?php echo JText::_( 'TL_ACCIONES' ); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <div id="srEstdGrntCnt" align="center" class="hide"> <p> <?php echo JText::_( 'COM_CONTRATO_SIN_REGISTROS' ); ?> </p> </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>