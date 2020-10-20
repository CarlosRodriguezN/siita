<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

//  Obtengo el id de registro del plan vigente
$regPPPP = '';
if ($this->lstPPPPs){
    foreach ($this->lstPPPPs as $i => $item){
        if  ( $item->vigentePln == 1 ) {
            $regPPPP = (int)$item->idRegPln;
        }
    }
}

?>

<div class="width-30 fltlft">
    <div class="m">
        <div>
            <table class="adminlist" id="lstPPPPs">
                <thead> 
                    <tr>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_PEI_FIELD_PLAN_VIGENCIA_LABEL', 'vigentePln' ); ?> </th>
                        <th> <?php echo JHtml::_('grid.sort', 'COM_PEI_FIELD_NOMBRE_PLN_LABEL', 'descripcionPln' ); ?> </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div id="srPppp" class='hide' align="center">
                <p><?php echo JText::_( 'COM_PEI_SIN_REGISTROS' ); ?></p>
            </div>
            <!-- Id de registro del POA para Gestion -->
            <input id="regPPPP" type="hidden" name="regPPPP"  value="<?php echo $regPPPP; ?>" />
        </div>
    </div>
</div>


<div class="width-70 fltrt"> 
    <fieldset class="adminform">
        <legend> <div id="lyPPPP"> <?php echo JText::_('COM_PEI_FIELD_PLAN_OBJETIVOS_TITLE'); ?> </div> </legend>
        <table id="tbLstObjetivosPPPP" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_DESCRIPCION_LABEL') ?></th>
                    <th align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_PRIORIDAD_LABEL') ?></th>
                    <th align="center"><?php echo JText::_( 'COM_PEI_FIELD_OBJETIVO_ALINEACION_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_('COM_PEI_FIELD_OBJETIVO_PLAN_ACCION_LABEL') ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div id="srObjPppp" class='hide' align="center">
            <p><?php echo JText::_( 'COM_PEI_SIN_REGISTROS' ); ?></p>
        </div>
    </fieldset>
</div>
