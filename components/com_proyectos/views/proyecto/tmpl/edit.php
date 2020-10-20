<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );
JHTML::_( 'behavior.modal' );
?>

<!-- Barra de Herramientas -->
<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo $this->title; ?> </h2>
        </div>
    </div>
</div>

<div id="element-box">
    <form action="<?php echo JRoute::_( 'index.php?option=com_proyectos&layout=edit&idEntidad=' . ( int ) $this->item->intIdEntidad_ent . '&intCodigo_pry=' . ( int ) $this->item->intCodigo_pry ); ?>" method="post" name="adminForm" id="proyecto-form" class="form-validate">
        <div id="tabs" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#pryPanelControl"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_PANEL_CONTROL_TITLE' ) ?></a></li>
                <li><a href="#pryDtaGral"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_DATOSGRLS_TITLE' ) ?></a></li>
                <li><a href="#pryDesc"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_RESUMEN_TITLE' ) ?></a></li>
                <li><a href="#responsables" title="<?php echo JText::_( 'COM_PROYECTOS_FIELD_RESPONSABLES_DESC' ); ?>"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_RESPONSABLES_TITLE' ) ?> </a></li>
                <li><a href="#pryObjetivos"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_OBJETIVOS_TITLE_LABEL' ) ?></a></li>
                <li><a href="#planesProyecto"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_PLANES_TITLE' ) ?></a></li>
                <li><a href="#pryUbGeo"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_UBCGEOGRAFICA_TITLE' ) ?></a></li>
                <li><a href="#pryMarcoLogico"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MARCOLOGICO_TITLE_LABEL' ) ?></a></li>
                <li><a href="#pryIndicadores"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_INDICADORES_TITLE' ) ?> </a></li>
                <li><a href="#pryImagenes"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_IMAGENES_TITLE' ) ?> </a></li>
                <li><a href="#pryContratos" title="<?php echo JText::_( 'COM_PROYECTOS_FIELD_CONTRATOS_DESC' ); ?>"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_CONTRATOS_TITLE' ) ?> </a></li>
                <li><a href="#pryConvenios" title="<?php echo JText::_( 'COM_PROYECTOS_FIELD_CONVENIOS_DESC' ); ?>"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_CONVENIOS_TITLE' ) ?> </a></li>
            </ul>

            <!-- Panel de control de una unidad de gestion -->
            <div class="m" id="pryPanelControl">
                <?php echo $this->loadTemplate( 'panelcontrol' ); ?>
            </div>

            <!-- DATOS GENERALES -->
            <div id="pryDtaGral">
                <div class="width-100">
                    <fieldset class="adminform">
                        <legend> Datos Proyecto </legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'datosGenerales' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>

                            <li>
                                <?php $mt = $this->form->getField( 'dcmMonto_total_stmdoPry' ); ?>
                                <?php echo $mt->label; ?>
                                <?php echo $mt->input; ?>
                            </li>

                        </ul>
                    </fieldset>
                </div>
            </div>

            <!-- PLANES -->
            <div id="planesProyecto" class="m">                
                <?php echo $this->loadTemplate( 'planesproyecto' ); ?>
            </div>
            
            <!-- RESUMEN DESCRIPTIVO -->
            <div id="pryDesc">
                <div class="width-100">
                    <fieldset class="adminform">
                        <legend> Datos Proyecto </legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'resumenDescriptivo' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php foreach( $this->strSecIntrv as $field ): ?>
                                <li>
                                    <?php $sec = strtolower( str_replace(" ", "", $field->strDescripcion_esi ) ); ?>
                                    <?php $fld = $this->form->getField( $sec ); ?>
                                    <?php echo $fld->label; ?>
                                    <?php echo $fld->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                </div>
            </div>

            <!-- MARCO LOGICO -->
            <div id="pryMarcoLogico">                
                <?php echo $this->loadTemplate( 'marcologico' ); ?>
            </div>

            <!-- UBICACION GEOGRAFICA -->
            <div id="pryUbGeo">
                <?php echo $this->loadTemplate( 'ubicaciongeografica' ); ?>
            </div>
            
            <!-- OBJETIVOS DE UN PROYECTO-->
            <div id="pryObjetivos" class="m">
                <!-- Objetivos -->
                <?php echo $this->loadTemplate( 'objetivos' ); ?>
            </div>

            <!-- ALINEACION DEL PROYECTO CON LA POLITICA NACIONAL 
            <div id="pryPnbv" class="m">
            <?php echo $this->loadTemplate( 'alineacionpnbv' ); ?>
            </div>
            -->
            <!-- INDICADORES -->
            <div id="pryIndicadores">
                <?php echo $this->loadTemplate( 'indicadores' ); ?>
            </div>

            <!-- IMAGENES -->
            <div id="pryImagenes">
                <?php echo $this->loadTemplate( 'imagenes' ); ?>
            </div>

            <!-- CONTRATOS -->
            <div class="m" id="pryContratos">
                <?php echo $this->loadTemplate( 'contratos' ); ?>
            </div>

            <!-- CONVENIOS -->
            <div class="m" id="pryConvenios">
                <?php echo $this->loadTemplate( 'convenios' ); ?>
            </div>
            <div class="m" id="responsables">
                <?php echo $this->loadTemplate( 'responsables' ); ?>
            </div>

            <div>
                <input type="hidden" name="task" value="proyecto.edit" />

                <input type="hidden" id="idProyecto" name="idProyecto" value="<?php echo ( int ) $this->item->intCodigo_pry; ?>">

                <input type="hidden" id="idObjetivo" name="idObjetivo" value="" />
                <input type="hidden" id="idAlineacion" name="idAlineacion" value="" />
                <input type="hidden" id="idEnfIg" name="idEnfIg" value="" />
                <input type="hidden" id="idEnfEc" name="idEnfEc" value="" />

                <input type="hidden" id="dataLineasBase" name="dataLineasBase" value="" />
                <input type="hidden" id="dtaPlanificacion" name="dtaPlanificacion" value="<?php echo (String)json_encode( $this->_dtaPlanes ); ?>" />
                <input type="hidden" id="dtaRoles" name="dtaRoles" value="<?php print htmlspecialchars( json_encode( $this->canDo ) ); ?>" />
                
                <?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </div>
    </form>
</div>

<?php echo $this->loadTemplate('progressblock'); ?>