<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100">
    <div id="tabsIndicadores" style="position: static; left: 20px; height: auto; width: 100%">
        <ul>
            <li> <a href="#indEco"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_INDECONOMICOS_TITLE') ?> </a> </li>
            <li> <a href="#benf"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_BENEFICIARIOS_TITLE') ?> </a> </li>
            <li> <a href="#gap"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_GAP_TITLE') ?> </a> </li>
            <li> <a href="#enfoqueIgualdad"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ENFOQUEIGUALDAD_TITLE') ?> </a> </li>
            <li> <a href="#enfoqueEcorae"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ENFOQUEECORAE_TITLE') ?> </a> </li>
            <li> <a href="#otrosInd"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_OTROS_TITLE') ?> </a> </li>
        </ul>

        <!-- Indicadores Economicos -->
        <div id="indEco">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_IND_ECO_TITLE') ?> </legend>

                <?php
                //  Financieros
                $tdf = $this->form->getField('intTasaDctoFin');
                $vanf = $this->form->getField('intValActualNetoFin');
                $tirf = $this->form->getField('intTIRFin');

                //  Economicos
                $tde = $this->form->getField('intTasaDctoEco');
                $vane = $this->form->getField('intValActualNetoEco');
                $tire = $this->form->getField('intTIREco');
                ?>

                <table width="100%" class="tablesorter" cellspacing="1">
                    <tr>
                        <th colspan="3" align="center"> <h2> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_IND_ECO_TITLE') ?>  </h2> </th>
                    </tr>
                    <!-- Economicos -->
                    <tr>
                        <th align="center"> <?php echo $tde->label; ?> </th>
                        <th align="center"> <?php echo $vane->label; ?> </th>
                        <th align="center"> <?php echo $tire->label; ?> </th>
                    </tr>

                    <tr>
                        <td align="center">
                            <!-- Tasa de descuento - Economico -->
                            <table cellpadding="0" cellspacing="0">
                                <tr align="center">
                                    <td align="center"> 
                                        <?php echo $tde->input; ?>
                                    </td>
                                    
                                    <!-- TD - Economico - Atributos de un Indicador -->
                                    <td>
                                        <a id="ECO0AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=1&tpoIndicador=eco&idRegIndicador=0&tpo=fijo&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a> 
                                    </td>
                                    
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- VAN - Economico -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $vane->input; ?> 
                                    </td>
                                    
                                    <!-- VAN - Economico - Atributos de un Indicador -->
                                    <td>
                                        <a id="ECO1AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=2&tpoIndicador=eco&idRegIndicador=1&tpo=fijo&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- TIR - Economico -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $tire->input; ?>
                                    </td>
                                    
                                    <!-- TIR - Economico - Atributos de un Indicador -->
                                    <td>
                                        <a id="ECO2AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=3&tpoIndicador=eco&idRegIndicador=2&tpo=fijo&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FINANCIEROS -->
                    <tr>
                        <th colspan="3" align="center" > <h2> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_IND_FINAN_TITLE') ?>  </h2> </th>
                    </tr>
                    <tr>
                        <th align="center"> <?php echo $tdf->label; ?> </th>
                        <th align="center"> <?php echo $vanf->label; ?> </th>
                        <th align="center"> <?php echo $tirf->label; ?> </th>
                    </tr>

                    <tr>
                        <td align="center"> 
                            <!-- Tasa de Descuento - Financiero -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $tdf->input; ?>
                                    </td>
                                    
                                    <!-- TIR - Economico - Atributos de un Indicador -->
                                    <td>
                                        <a id="FIN0AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=4&tpoIndicador=fin&idRegIndicador=0&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- VAN - Financiero -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $vanf->input; ?>
                                    </td>
                                    
                                    <!-- TIR - Economico - Atributos de un Indicador -->
                                    <td>
                                        <a id="FIN1AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=5&tpoIndicador=fin&idRegIndicador=1&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- TIR - Financiero -->
                            <table cellpadding="0"  cellspacing="0">

                                <tr>
                                    <td> 
                                        <?php echo $tirf->input; ?>
                                    </td>
                                    
                                    <!-- TIR - Financiero - Atributos de un Indicador -->
                                    <td>
                                        <a id="FIN2AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=6&tpoIndicador=fin&idRegIndicador=2&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>

        <!-- Beneficiarios -->
        <div id="benf">
            <fieldset class="adminform">
                <legend>&nbsp;Beneficiarios&nbsp;</legend>
                <?php
                //  Beneficiarios Directos
                $bdh = $this->form->getField('intBenfDirectoHombre');
                $bdm = $this->form->getField('intBenfDirectoMujer');
                $bdt = $this->form->getField('intTotalBenfDirectos');

                //  Beneficiarios Indirectos
                $bidh = $this->form->getField('intBenfIndDirectoHombre');
                $bidm = $this->form->getField('intBenfIndDirectoMujer');
                $bidt = $this->form->getField('intTotalBenfIndDirectos');
                ?>
                <table width="100%" class="tablesorter" cellspacing="1">
                    <!-- Beneficiarios Directos -->
                    <tr>
                        <th align="center"> <?php echo $bdh->label; ?> </th>
                        <th align="center"> <?php echo $bdm->label; ?> </th>
                        <th align="center"> <?php echo $bdt->label; ?> </th>
                    </tr>

                    <tr>
                        <td align="center">
                            <!-- Beneficiarios Directos Hombres -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bdh->input; ?>
                                    </td>
                                    <!-- Hombre - Beneficiarios Directos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BD0AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=7&tpo=m&tpoIndicador=bd&idRegIndicador=0&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- Beneficiarios Directos Mujeres -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bdm->input; ?>
                                    </td>
                                    
                                    <!-- Mujer - Beneficiarios Directos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BD1AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=8&tpo=f&tpoIndicador=bd&idRegIndicador=1&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- Beneficiarios Directos Total -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bdt->input; ?> 
                                    </td>
                                    
                                    <!-- Total - Beneficiarios Directos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BD2AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=9&tpo=t&tpoIndicador=bd&idRegIndicador=2&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>                                            
                        </td>
                    </tr>

                    <!-- Beneficiarios Indirectos -->
                    <tr>
                        <th align="center"> <?php echo $bidh->label; ?> </th>
                        <th align="center"> <?php echo $bidm->label; ?> </th>
                        <th align="center"> <?php echo $bidt->label; ?> </th>
                    </tr>

                    <tr>
                        <td align="center">
                            <!-- Beneficiarios inDirectos Hombres -->
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bidh->input; ?>
                                    </td>

                                    <!-- Hombres - Beneficiarios Indirectos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BI0AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=10&tpo=m&tpoIndicador=bi&idRegIndicador=0&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center"> 
                            <!-- Beneficiarios inDirectos Mujeres -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bidm->input; ?>
                                    </td>

                                    <!-- Mujeres - Beneficiarios Indirectos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BI1AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=11&tpo=f&tpoIndicador=bi&idRegIndicador=1&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td align="center">
                            <!-- Beneficiarios inDirectos Total -->
                            <table cellpadding="0"  cellspacing="0">
                                <tr>
                                    <td> 
                                        <?php echo $bidt->input; ?>
                                    </td>
                                    
                                    <!-- Total - Beneficiarios Indirectos - Atributos de un Indicador -->
                                    <td>
                                        <a id="BI2AI" href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=12&tpo=t&tpoIndicador=bi&idRegIndicador=2&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                                            <img src="<?php echo JText::_( 'COM_CONVENIOS_RG_ATRIBUTO' ); ?>" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_RG_ATRIBUTO' ) ?>" >
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>

        <!-- Grupo de Atencion Prioritarios -->
        <div id="gap" class="m">
            <!-- Grupos de Atencion Prioritarios Agregados al sistema -->
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_GPOATTPRIORITARIA_LABEL') ?>&nbsp;</legend>
                    <div id="lstGAP">
                        <!-- Genera una Tabla con informacion de areas y Especialidades de los cursos a dictar -->
                        <table width="100%" id="tbLstGAP" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_GPOATTPRIORITARIA_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_GAPMASCULINO_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_GAPFEMENINO_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_GAPTOTAL_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
            </div>

            <!-- Formulario de Registro de Indicadores -->
            <div class="width-50 fltrt">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ENFOQUEATRENCIONPRIORITARIA_LEG_LABEL') ?> </legend>
                    <?php
                    //  Grupos de Atencion Prioritaria
                    $cbGAP = $this->form->getField('cbGpoAtencionPrioritario');
                    $gapMasculino = $this->form->getField('intGAPMasculino');
                    $gapFemenino = $this->form->getField('intGAPFemenino');
                    $gapTotal = $this->form->getField('intGAPTotal');

                    //  Obtengo campo Lista GAP - Oculto
                    $hddLstGAP = $this->form->getField('dataGAP');
                    ?>

                    <table width="100%" id="tbGAP" class="tablesorter" cellspacing="1">
                        <!-- Beneficiarios Directos -->
                        <thead>
                            <tr>
                                <th align="center"> <?php echo $cbGAP->label; ?> </th>
                                <th align="center" colspan="2"> <?php echo $cbGAP->input; ?> </th>
                            </tr>
                        </thead>

                        <tr>
                            <th align="center"> <?php echo $gapMasculino->label; ?> </th>
                            <th align="center"> <?php echo $gapFemenino->label; ?> </th>
                            <th align="center"> <?php echo $gapTotal->label; ?> </th>
                        </tr>

                        <tr>
                            <td align="center">
                                <!-- Beneficiarios Masculinos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $gapMasculino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Beneficiarios Femeninos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $gapFemenino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Beneficiarios Total -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $gapTotal->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" colspan="3"> 
                                <input id="btnAddGAP" type="button" value="<?php echo JText::_('COM_CONTRATOS_BT_INDICADOR_AGP_ADDEI') ?>"> 
                                <input id="btnLimpiarGAP" type="button" value="Limpiar"> 
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </div>

        <!-- ENFOQUE IGUALDAD -->
        <div id="enfoqueIgualdad" class="m">
            <!-- Formulario de contenido -->
            <div class="width-40 fltrt">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ENFOQUEIGUALDAD_LEG_LABEL') ?> </legend>
                    <?php
                    //  formulario enfoque de igualdad
                    $cbEI = $this->form->getField('cbEnfoqueIgualdad');
                    $idEI = $this->form->getField('idEnfoqueIgualdad');

                    $EIMasculino = $this->form->getField('intEIMasculino');
                    $EIFemenino = $this->form->getField('intEIFemenino');
                    $EITotal = $this->form->getField('intEITotal');

                    //  Obtengo campo Lista EI
                    $hddLstEI = $this->form->getField('dataEnfIgu');
                    ?>

                    <!-- Formulario Lista de Indicadores Enfoque Ecorae -->
                    <table width="80%" id="tbEE" class="tablesorter" cellspacing="1">
                        <thead>
                            <!-- Tipos de Enfoque de Igualdad -->
                            <tr>
                                <th align="center"> <?php echo $cbEI->label; ?> </th>
                                <th align="center" colspan="2"> <?php echo $cbEI->input; ?> </th>
                            </tr>

                            <!-- Enfoque de Igualdad -->
                            <tr>
                                <th align="center"> <?php echo $idEI->label; ?> </th>
                                <th align="center" colspan="2"> <?php echo $idEI->input; ?> </th>
                            </tr>
                        </thead>

                        <tr>
                            <th align="center"> <?php echo $EIMasculino->label; ?> </th>
                            <th align="center"> <?php echo $EIFemenino->label; ?> </th>
                            <th align="center"> <?php echo $EITotal->label; ?> </th>
                        </tr>

                        <tr>
                            <td align="center">
                                <!-- Enfoque de Igualdad Masculinos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EIMasculino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Enfoque de Igualdad Femeninos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EIFemenino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Enfoque de Igualdad Total -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EITotal->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" colspan="3"> 
                                <input id="btnAddEI" type="button" value="<?php echo JText::_('COM_CONTRATOS_BT_INDICADOR_EI_ADDEI') ?> "> 
                                <input id="btnLimpiarEI" type="button" value="<?php echo JText::_('COM_CONTRATOS_BT_INDICADOR_EI_CLEI') ?> "> 
                                <?php echo $hddLstEI->input; ?>
                            </td>
                        </tr>
                    </table>   
                </fieldset>
            </div>

            <!-- Tabla con informacion -->
            <div class="width-60 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_LTINDICADORESIGUALDA_LABEL') ?>&nbsp;</legend>
                    <!-- Genera una Tabla con informacion de areas y Especialidades de los cursos a dictar -->
                    <table width="100%" id="lstEnfIgu" class="tablesorter" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_EI_LABEL') ?> </th>
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_TEI_LABEL') ?> </th>
                                <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EIMASCULINO_LABEL') ?> </th>
                                <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EIFEMENINO_LABEL') ?> </th>
                                <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EITOTAL_LABEL') ?> </th>
                                <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>

        <!-- ENFOQUE ECORAE -->
        <div id="enfoqueEcorae" class="m">
            <!-- Formulario de acceso a informacion -->
            <div class="width-50 fltrt">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ENFOQUEECORAE_LEG_LABEL') ?></legend>
                    <?php
                    //  Grupos de Atencion Prioritaria
                    $cbEE = $this->form->getField('cbEnfoqueEcorae');
                    $EEMasculino = $this->form->getField('intEnfEcoMasculino');
                    $EEFemenino = $this->form->getField('intEnfEcoFemenino');
                    $EETotal = $this->form->getField('intEnfEcoTotal');

                    //  Obtengo campo Lista EE
                    $hddLstEE = $this->form->getField('dataEnfEco');

                    $hddLstEE = $this->form->getField('dataIndicadores');
                    ?>

                    <table width="100%" id="tbEE" class="tablesorter" cellspacing="1">
                        <!-- Beneficiarios Directos -->
                        <thead>
                            <tr>
                                <th align="center"> <?php echo $cbEE->label; ?> </th>
                                <th align="center" colspan="2"> <?php echo $cbEE->input; ?> </th>
                            </tr>
                        </thead>

                        <tr>
                            <th align="center"> <?php echo $EEMasculino->label; ?> </th>
                            <th align="center"> <?php echo $EEFemenino->label; ?> </th>
                            <th align="center"> <?php echo $EETotal->label; ?> </th>
                        </tr>

                        <tr>
                            <td align="center">
                                <!-- Enfoque Ecorae Masculinos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EEMasculino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Enfoque Ecorae Femeninos -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EEFemenino->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td align="center">
                                <!-- Enfoque Ecorae Total -->
                                <table cellpadding="0"  cellspacing="0">
                                    <tr>
                                        <td> 
                                            <?php echo $EETotal->input; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" colspan="3"> 
                                <input id="btnAddEnfEco" type="button" value="<?php echo JText::_('COM_CONTRATOS_BT_INDICADOR_EI_ADDEE') ?> "> 
                                <input id="btnLimpiarEnfEco" type="button" value="<?php echo JText::_('COM_CONTRATOS_BT_INDICADOR_EI_CLEE') ?> "> 
                                <?php echo $hddLstEE->input; ?>
                            </td>
                        </tr>
                    </table>    
                </fieldset>
            </div>

            <!-- Enfoque Ecorae Agregados al sistema -->
            <div class="width-50 fltlft">
                <fieldset class="adminform">
                    <legend>&nbsp;<?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_LTENFOQUEECORAE_LABEL') ?>&nbsp;</legend>
                    <div id="lstEnfEco">
                        <!-- Genera una Tabla con informacion de areas y Especialidades de los cursos a dictar -->
                        <table width="100%" id="lstEnfEco" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_EE_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EEMASCULINO_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EEFEMENINO_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_PROYECTO_EETOTAL_LABEL') ?> </th>
                                    <th align="center" colspan="2"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
            </div>
        </div>

        <!-- Otros Indicadores -->
        <div id="otrosInd" class="m">
            <!-- Tabla de Otros Indicadores del Proyecto -->
            <div class="width-100">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_OTROINDREG_TITLE') ?> </legend>
                    
                    <div class="clr"></div>
                    
                    <a href="index.php?option=com_indicadores&view=indicador&layout=edit&idIndicador=0&tpoIndicador=oi&idRegIndicador=-1&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ANCHO' ); ?>, y: <?php echo JText::_( 'COM_CONTRATOS_POPUP_ALTO' ); ?>}}"> 
                        <div id="NI2AI" title='<?php echo JText::_('BTN_AGREGAR_TITLE') ?>'> 
                            <label style="min-width: 0px"> <?php echo JText::_('BTN_AGREGAR') ?> </label>
                            <img src="<?php echo JText::_( 'COM_CONVENIOS_VD_ATRIBUTO' ) ?>" id="ai-bd-0" title="<?php echo JText::_( 'COM_CONVENIOS_TITLE_VD_ATRIBUTO' ) ?>" >
                        </div>
                    </a>
                    
                    <div class="clr"></div>
                    
                    <table id="lstOtrosInd" width="100%" class="tablesorter" cellspacing="1">
                        <thead>
                            <tr>
                                <th align="center" style="width: 20px"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ESTADO_TITLE') ?> </th>
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_NOMBREOTROIND_TITLE') ?> </th>
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_DESCOTROIND_TITLE') ?> </th>
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_VALOROTROIND_TITLE') ?> </th>                                
                                <th align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_FORMULAOTROIND_TITLE') ?> </th>
                                <th colspan="2" align="center"> <?php echo JText::_('COM_CONTRATOS_FIELD_INDICADOR_ACCIONOTROIND_TITLE') ?> </th>
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