<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
?>
<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo JText::_( 'COM_CONTRATOS_CONTRATO' ); ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <div class="m">

        <form action="<?php echo JRoute::_( 'index.php?option=com_contratos&layout=edit&intIdContrato_ctr=' . (int) $this->item->intIdContrato_ctr ); ?>" method="post" name="adminForm" id="contratos-form" class="form-validate">

            <div id="contratosTab" class="width-100 fltlft">
                <ul>
                    <li><a href="#ctrPanelControl"> <?php echo JText::_( 'COM_CONTRATOS_TAB_PANEL_CONTROL' ); ?></a></li>
                    <li><a href="#dataGen">         <?php echo JText::_( 'COM_CONTRATOS_TAB_GENERAL' ); ?></a></li>
                    <li><a href="#objetivos">       <?php echo JText::_( 'COM_CONTRATOS_TAB_OBJETIVOS' ); ?></a></li>
                    <li><a href="#responsables">    <?php echo JText::_( 'COM_CONTRATOS_TAB_RESPONSABLES' ); ?></a></li>
                    <li><a href="#ubicacion">       <?php echo JText::_( 'COM_CONTRATOS_TAB_UBICACION' ); ?></a></li>
                    <li><a href="#planesProyecto">  <?php echo JText::_( 'COM_CONTRATOS_FIELD_PROYECTO_PLANES_TITLE' ) ?></a></li>
                    <li><a href="#garantias">       <?php echo JText::_( 'COM_CONTRATOS_TAB_GARANTIAS' ); ?></a></li>
                    <li><a href="#contratistas">    <?php echo JText::_( 'COM_CONTRATOS_TAB_CONTRATISTA' ); ?></a></li>
                    <li><a href="#fiscalizadores">  <?php echo JText::_( 'COM_CONTRATOS_TAB_FISCALIZADORES' ); ?></a></li>
                    <li><a href="#multas">          <?php echo JText::_( 'COM_CONTRATOS_TAB_MULTAS' ); ?></a></li>
                    <li><a href="#prorrogas">       <?php echo JText::_( 'COM_CONTRATOS_TAB_PRORROGAS' ); ?></a></li>
                    <li><a href="#facturas">        <?php echo JText::_( 'COM_CONTRATOS_TAB_FACTURAS' ); ?></a></li>
                    <li><a href="#planPago">        <?php echo JText::_( 'COM_CONTRATOS_TAB_PLANPAGO' ); ?></a></li>
                    <li><a href="#indicadores">     <?php echo JText::_( 'COM_CONTRATOS_FIELD_PROGRAMA_INDICADORES_TITLE' ) ?></a></li>
                </ul>
                
                <!-- Panel de control de una unidad de gestion -->
                <div class="m" id="ctrPanelControl">
                    <?php echo $this->loadTemplate('panelcontrol'); ?>
                </div>
                
                <!--INFORMACION GENERAL-->
                <div id="dataGen">
                    <?php echo $this->loadTemplate( 'datagen' ); ?>
                </div>

                <!-- PLANES -->
                <div id="planesProyecto" class="m">                
                    <?php echo $this->loadTemplate( 'planesproyecto' ); ?>
                </div>

                <!-- GARANTIAS -->
                <div id="garantias">
                    <?php echo $this->loadTemplate( 'garantias' ); ?>
                </div>
                
                <!-- MULTAS -->
                <div id="multas">
                    <?php echo $this->loadTemplate( 'multas' ); ?>
                </div>
                
                <!--Contratistas-->
                <div id="contratistas">      
                    <?php echo $this->loadTemplate( 'contratista' ); ?>
                </div>
                
                <!--Fiscalizadores-->
                <div id="fiscalizadores">      
                    <?php echo $this->loadTemplate( 'fiscalizadores' ); ?>
                </div>
                
                <!-- prorrogas -->
                <div id="prorrogas">      
                    <?php echo $this->loadTemplate( 'prorrogas' ); ?>
                </div>
                
                <!-- Facturas -->
                <div id="facturas">      
                    <?php echo $this->loadTemplate( 'facturas' ); ?>
                </div>
                
                <div id="planPago">      
                    <?php echo $this->loadTemplate( 'planPago' ); ?>
                </div>
                
                <!-- Unidades territoriales -->
                <div id="ubicacion">      
                    <?php echo $this->loadTemplate( 'ubicacion' ); ?>
                </div>  
                
                <!-- Gestion de Indicadores -->
                <div id="indicadores" class="width-100">
                    <?php echo $this->loadTemplate( 'indicadores' ); ?> 
                </div>

                <!--Otros-->
                <div id="objetivos">      
                    <?php echo $this->loadTemplate( 'objetivos' ); ?>
                </div>
                
                <!--Otros-->
                <div id="responsables">      
                    <?php echo $this->loadTemplate( 'responsables' ); ?>
                </div>            
            </div>
            <div>
                <input type="hidden" name="task" value="contrato.edit" />
                <input type="hidden" id="idPrograma" name="programa" value="<?php echo $this->idPrograma ?>">
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </form>

    </div>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>
