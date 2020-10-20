<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');


//  Adjunto libreria para la creacion de archivos Excel
jimport( 'excel.PHPExcel' );

//  Adjunto libreria para la creacion de archivos Excel
jimport( 'word.PHPWord' );

//  Adjunto libreria para creacion de archivos PDF
require_once( JPATH_LIBRARIES.DS.'dompdf'.DS.'dompdf_config.inc.php' );


/**
 * 
 *  Controlador Fases
 * 
 */
class ReporteControllerOrganigrama extends JControllerForm
{
    protected $view_list = 'organigramas';
    
    protected function allowAdd()
    {
        return true;
    }
    
    protected function allowEdit()
    {
        return true;
    }
    
    function add()
    {
        parent::add();
    }
    
    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=proyectos', false ) );
    }
    
    /**
     * 
     * Registro Icono de un proyecto
     * 
     * @return type
     */
    public function registroIcono()
    {
        //  Verifico la existencia de imagenes en el formulario
        if( isset( $_FILES["iconoPry"] ) ){
            //  Accedo al modelo del componente com_programa
            $modelo = $this->getModel();
            return $modelo->saveImagesProyecto();
        }else{
            return true;
        }
    }
    
    /**
     * 
     * Gestiono el registro de imagenes
     * 
     * @return boolean
     */
    public function registroImagenes()
    {
        //  Verifico la existencia de imagenes en el formulario
        if( isset( $_FILES["imagesPry"] ) ){
            //  Accedo al modelo del componente com_programa
            $modelo = $this->getModel();
            return $modelo->saveImagesProyecto();
        }else{
            return true;
        }
    }
    
    /**
     * 
     * Asigno a un proyecto la propuesta de un proyecto
     * 
     * @return type
     */
    public function addCanastaProyecto()
    {
        $modelo = $this->getModel();
        $lstPropuestas = JRequest::getVar( 'cid' );
        $idProyecto = JRequest::getVar( 'cbLstProyectos' );
        return $modelo->asignarPropuestasProyecto( $idProyecto, $lstPropuestas );
    }
    
    
    /**
     * 
     * Creo un NUEVO proyectos a partir de un lista de propuestas de proyectos
     * 
     * @return type
     */
    public function crearProyecto()
    {
        $modelo = $this->getModel();
        $lstPropuestas = JRequest::getVar( 'cid' );
        
        return $modelo->crearProyectoConPropuestas( $lstPropuestas );
    }
    
    
    /**
     *  
     *  Retono un archivo en formato PDF, con informacion General y detallada 
     *  de un determinado proyecto
     * 
     */
    public function pdf()
    {       
        $url = 'index.php?option=' . $this->option . '&view=proyecto&format=pdf&tmpl=component';
        $this->setRedirect( JRoute::_( $url, false ) );

        return;
    }
    
    
    /**
     *
     * Seteo informacion que forma parte del reporte
     * 
     * @param type $idProyecto  identificador del reporte
     * 
     */
    private function _setDataReporte( $idProyecto )
    {
        //  Accedo al modelo 'ProgramasModelPrograma'
        $mdProyecto = $this->getModel( 'Proyecto', 'ProyectosModel' );
        
        //  Datos generales de un proyecto
        $this->_dataProyecto = $mdProyecto->getInfoProyecto( $idProyecto );

        //  Datos de indicadores Financieros
        $this->_dataIndFinancieros = $mdProyecto->getDataIndFinancieros( $idProyecto );
        
        //  Datos de Indicadores Sociales
        $this->_dataIndBeneficiarios = $mdProyecto->getDataIndBeneficiarios( $idProyecto );

        //  Datos de indicadores GAP
        $this->_dataIndGAP = $mdProyecto->getIndGAP( $idProyecto );
        
        //  Datos de Otros Indicadores registrados pertenecientes a un proyecto
        $this->_dataOtrosIndicadores = $mdProyecto->getOtrosIndicadores( $idProyecto );
        
        //  Datos de Unidades Territoriales pertenecientes a un determinado proyecto
        $this->_dataUndTerritorial = $mdProyecto->getDataUndTerritorial( $idProyecto );
        
        //  Datos de Objetivo General y Especifico perteneciente a un determinado proyecto
        $this->_dataObjetivos = $mdProyecto->getDataObjetivos( $idProyecto );
    }
    
    
    /**
     *  Formato de descarga de un archivo PDF
     * 
     *  @param type $info   Informacion de detalle de un programa
     *  @return <HTML>      Detalle de un programa con formateado HTML
     */
    public function getHTMLPrograma()
    {
        $retval = '<link rel="stylesheet" href="'. JURI::root(). 'libraries/dompdf/www/test/css/print_static.css' .'" type="text/css" />';
        $retval .= ' <div id="body">
                        <div id="section_header"> </div>';
        
        #   Identificacion del Proyecto
        $retval .= '    <div id="content">';
        $retval .= '        <div class="page" style="font-size: 7pt">
                                <table style="width: 100%;" class="header">
                                    <tr>
                                        <td> <img src="images/LogoPresidencia.png"> </td>
                                        <td align="right"> <img src="images/logo_ecorae.png"> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> <h1 style="text-align: center"> INFORME T&Eacute;CNICO DE FINANCIAMIENTO </h1> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> <h1 style="text-align: left"> Informe T&eacute;cnico de Financiamiento: 1</h1></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> <h1 style="text-align: left"> Fecha: 18 de Diciembre del 2012</h1></td>
                                    </tr>
                                </table>';
        $retval .= '    </div>';
        
        $retval .= '    <div id="content">';

        #   Identificacion del problema
        $retval .= '        <h1>1: IDENTIFICACI&Oacute;N DEL PROYECTO</h1>
                            <table style="width: 100%; font-size: 8pt;">
                                <tr>
                                    <td colspan="5">Titulo: <strong>'.  utf8_decode( $this->_dataProyecto[0]->nomProyecto ) .'</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5">Entidad Ejecutora: <strong>ECORAE</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <table class="change_order_items">
                                            <tr>
                                                <td colspan="4">
                                                    <h2>Ubicaci&oacute;n:</h2>
                                                </td>
                                            </tr>
                                            <tbody>
                                                <tr>
                                                    <th>Provincia</th>
                                                    <th>Canton</th>
                                                    <th>Parroquia</th>
                                                    <th>Comunidad</th>
                                                </tr>';
        
        if( $this->_dataUndTerritorial && count( $this->_dataUndTerritorial ) > 0 ){
            $class = "odd_row";
            foreach( $this->_dataUndTerritorial as $dpa ){
                $class = ( $class == "even_row" )   ? "odd_row" 
                                                    : "even_row";

                $retval .= '<tr class='. $class .'>';
                $retval .= '    <td align="center">'. $dpa->provincia .'</td>';
                $retval .= '    <td align="center">'. $dpa->canton .'</td>';
                $retval .= '    <td align="center">'. $dpa->parroquia .'</td>';
                $retval .= '    <td align="center">NO Definido</td>';
                $retval .= '</tr>';
            }
        }else{
            $retval .= '<tr class="even_row">';
            $retval .= '    <td align="center" colspan="4">SIN REGISTROS DISPONIBLES</td>';
            $retval .= '</tr>';
        }

        $retval .= '                        </tbody>
                                        </table>
                                    </td>
                                </tr>';

        $retval .= '            <tr>
                                    <td colspan="5">Aporte de ECORAE: <strong>$ '. number_format( $this->_dataProyecto[0]->monto, 2, ',', '.' ) .' Usd. </strong></td>
                                </tr>';

        $retval .= '            <tr>
                                    <td colspan="5">Duraci&oacute;n: <strong>'. $this->_dataProyecto[0]->duracion .' - '. $this->_dataProyecto[0]->undMedida .'</strong></td>
                                </tr>';

        $retval .= '        </table>';
        $retval .= '    </div>';

        #   Antecedentes
        $retval .= '    <div id="content">';
        $retval .= '        <h1>2: ANTECEDENTES</h1>';
        $retval .= '    </div>';
        
        #   Diagnostico y Problema
        $retval .= '    <div id="content">';
        $retval .= '        <h1>3: DIAGN&Oacute;STICO Y PROBLEMA</h1>';
        $retval .= '        
                            <li>3.1.: Identificaci&oacute;n, descripci&oacute;n y diagn&oacute;stico del problema a resolver
                                <p>&nbsp;</p>
                            </li>

                            <li>3.2.: L&iacute;nea Base, establecer indicadores cuantificados de:
                                <p>&nbsp;</p>
                            </li>';
        $retval .= '    </div>';
        
        #   Objetivos
        $retval .= '    <div id="content">';
        $retval .= '        <h1>4: OBJETIVOS</h1>';
        $retval .= '        <li>4.1.: General
                                <p>&nbsp;</p>
                            </li>

                            <li>4.2.: Especifico
                                <p>&nbsp;</p>
                            </li>';
        $retval .= '    </div>';

        #   Indicadores
        $retval .= '    <div id="content">';
        $retval .= '        <h1>5: INDICADORES</h1>';

        #   Indicadores Economicos
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="3">
                                        <h2>Indicadores Economicos</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th>Tasa de Descuento ( % )</th>
                                        <th>Valor Actual Neto ( $ ) </th>
                                        <th>Tasa Interna de Retorno ( $ )</th>
                                    </tr>';

        if( !empty( $this->_dataIndFinancieros[0] ) ){
            $td = ( !is_null( $this->_dataIndFinancieros[0]->TD ) && is_numeric( $this->_dataIndFinancieros[0]->TD ) ) ? number_format( $this->_dataIndFinancieros[0]->TD, 2, ',', '.' ): 0;
            $van = ( !is_null( $this->_dataIndFinancieros[0]->VAN ) && is_numeric( $this->_dataIndFinancieros[0]->VAN ) ) ? number_format( $this->_dataIndFinancieros[0]->VAN, 2, ',', '.' ): 0;
            $tir = ( !is_null( $this->_dataIndFinancieros[0]->TIR ) && is_numeric(  $this->_dataIndFinancieros[0]->TIR ) ) ? number_format(  $this->_dataIndFinancieros[0]->TIR, 2, ',', '.' ): 0;
            
            $retval .= '            <tr class="even_row">
                                        <td align="center">'. $td .'</td>
                                        <td align="center">'. $van .'</td>
                                        <td align="center">'. $tir .'</td>
                                    </tr>';
        }else{
            $retval .= '            <tr class="even_row">
                                        <td align="center" colspan="3">SIN REGISTROS DISPONIBLES</td>
                                    </tr>';
        }
        
        $retval .= '            </tbody>
                            </table>';

        #   Indicadores Financieros
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="3">
                                        <h2>Indicadores Financieros</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th>Tasa de Descuento ( % )</th>
                                        <th>Valor Actual Neto ( $ ) </th>
                                        <th>Tasa Interna de Retorno ( $ )</th>
                                    </tr>';
        
        if( !empty( $this->_dataIndFinancieros[1] ) ){
            $td = ( !is_null( $this->_dataIndFinancieros[1]->TD ) && is_numeric( $this->_dataIndFinancieros[1]->TD ) )  ? number_format( $this->_dataIndFinancieros[1]->TD, 2, ',', '.' ) : 0;
            $van = ( !is_null( $this->_dataIndFinancieros[1]->VAN ) && is_numeric( $this->_dataIndFinancieros[1]->VAN ) )   ? number_format( $this->_dataIndFinancieros[1]->VAN, 2, ',', '.' ) : 0;
            $tir = ( !is_null( $this->_dataIndFinancieros[1]->TIR ) && is_numeric(  $this->_dataIndFinancieros[1]->TIR ) )  ? number_format(  $this->_dataIndFinancieros[1]->TIR, 2, ',', '.' ): 0;
            
            $retval .= '            <tr class="even_row">
                                        <td align="center">'. $td .'</td>
                                        <td align="center">'. $van .'</td>
                                        <td align="center">'. $tir .'</td>
                                    </tr>';
            
        }else{
            $retval .= '            <tr class="even_row">
                                        <td align="center" colspan="3">SIN REGISTROS DISPONIBLES</td>
                                    </tr>';
        }
        
        $retval .= '            </tbody>
                            </table>';

        #   Indicadores Beneficiarios Directos
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="3">
                                        <h2>Beneficiarios Directos</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th>Hombres</th>
                                        <th>Mujeres </th>
                                        <th>Total</th>
                                    </tr>';
        
        if( !empty( $this->_dataIndBeneficiarios[0] ) ){
            
            $bh = ( !is_null( $this->_dataIndBeneficiarios[0]->BH ) && is_numeric( $this->_dataIndBeneficiarios[0]->BH ) ) ? number_format( $this->_dataIndBeneficiarios[0]->BH, 2, ',', '.' ) : 0;
            $bm = ( !is_null( $this->_dataIndBeneficiarios[0]->BM ) && is_numeric( $this->_dataIndBeneficiarios[0]->BM ) ) ? number_format( $this->_dataIndBeneficiarios[0]->BM, 2, ',', '.' ) : 0;
            $tb = ( !is_null( $this->_dataIndBeneficiarios[0]->TB ) && is_numeric( $this->_dataIndBeneficiarios[0]->TB ) ) ? number_format( $this->_dataIndBeneficiarios[0]->TB, 2, ',', '.' ) : 0;
            
            $retval .= '            <tr class="even_row">
                                        <td align="center">'. $bh .'</td>
                                        <td align="center">'. $bm .'</td>
                                        <td align="center">'. $tb .'</td>
                                    </tr>';
        }else{
            $retval .= '            <tr class="even_row">
                                        <td align="center" colspan="3">SIN REGISTRO DISPONIBLES</td>
                                    </tr>';
        }
            
        
        $retval .= '            </tbody>
                            </table>';

        #   Indicadores Beneficiarios Indirectos
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="3">
                                        <h2>Beneficiarios Indirectos</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th>Hombres</th>
                                        <th>Mujeres </th>
                                        <th>Total</th>
                                    </tr>';
        
        if( !empty( $this->_dataIndBeneficiarios[1] ) ){
            $bh = ( !is_null( $this->_dataIndBeneficiarios[1]->BH ) && is_numeric( $this->_dataIndBeneficiarios[1]->BH ) ) ? number_format( $this->_dataIndBeneficiarios[1]->BH, 2, ',', '.' ) : 0;
            $bm = ( !is_null( $this->_dataIndBeneficiarios[1]->BM ) && is_numeric( $this->_dataIndBeneficiarios[1]->BM ) ) ? number_format( $this->_dataIndBeneficiarios[1]->BM, 2, ',', '.' ) : 0;
            $tb = ( !is_null( $this->_dataIndBeneficiarios[1]->TB ) && is_numeric( $this->_dataIndBeneficiarios[1]->TB ) ) ? number_format( $this->_dataIndBeneficiarios[1]->TB, 2, ',', '.' ) : 0;
            
            $retval .= '            <tr class="even_row">
                                        <td align="center">'. $bh .'</td>
                                        <td align="center">'. $bm .'</td>
                                        <td align="center">'. $tb .'</td>
                                    </tr>';
        }else{
            $retval .= '            <tr class="even_row">
                                        <td align="center" colspan="3">SIN REGISTRO DISPONIBLES</td>
                                    </tr>';
        }
        
        $retval .= '            </tbody>
                            </table>';

        #   Indicadores - Grupos de Atencion Prioritaria
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="4">
                                        <h2>Grupo de Atenci&oacute;n Prioritaria</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th align="center">Grupos de Atenci&oacute;n Prioritaria</th>
                                        <th align="center">Masculino</th>
                                        <th align="center">Femenino</th>
                                        <th align="center">Total</th>
                                    </tr>';
        
        if( $this->_dataIndGAP && count( $this->_dataIndGAP ) > 0 ){
            $class = "odd_row";
            foreach( $this->_dataIndGAP as $gap ){
                $class = ( $class == "even_row" )   ? "odd_row" 
                                                    : "even_row";

                $retval .= '        <tr class='. $class .'>';
                $retval .= '            <td align="center">'. utf8_decode( $gap->descripcion ) .'</td>';
                $retval .= '            <td align="center">'. number_format( $gap->MASCULINO, 2, ',', '.' ) .'</td>';
                $retval .= '            <td align="center">'. number_format( $gap->FEMENINO, 2, ',', '.' ) .'</td>';
                $retval .= '            <td align="center">'. number_format( $gap->TOTAL, 2, ',', '.' ) .'</td>';
                $retval .= '        </tr>';
            }
        }else{
            $retval .= '            <tr class="even_row">
                                        <td colspan="4" align="center">SIN REGISTROS DISPONIBLES</td>
                                    </tr>';
        }
        
        $retval .= '            </tbody>
                            </table>';

        #   Indicadores - Otros Indicadores
        $retval .= '        <table class="change_order_items">
                                <tr>
                                    <td colspan="5">
                                        <h2>Otros Indicadores</h2>
                                    </td>
                                </tr>
                                <tbody>
                                    <tr>
                                        <th align="center">Nombre</th>
                                        <th align="center">Descripci&oacute;n</th>
                                        <th align="center">Unidad de An&aacute;lisis</th>
                                        <th align="center">Valor</th>
                                        <th align="center">Unidad de Medida</th>
                                    </tr>';
        
        if( $this->_dataOtrosIndicadores && count( $this->_dataOtrosIndicadores ) > 0 ){
            $class = "odd_row";
            foreach( $this->_dataOtrosIndicadores as $otros ){
                $class = ( $class == "even_row" )   ? "odd_row" 
                                                    : "even_row";

                $retval .= '<tr class='. $class .'>';
                $retval .= '    <td align="center">'. utf8_decode( $otros->strnombre_ind ) .'</td>';
                $retval .= '    <td align="left">'. utf8_decode( $otros->strdescripcion_ind ) .'</td>';
                $retval .= '    <td align="center">'. utf8_decode( $otros->strdescripcion_unianl ) .'</td>';
                $retval .= '    <td align="center">'. number_format( $otros->dcmValor, 2, ',', '.' ) .'</td>';
                $retval .= '    <td align="center">'. utf8_decode( $otros->strdescripcion_unimed ) .'</td>';

                $retval .= '</tr>';
            }
        }else{
            $retval .= '<tr class="even_row">';
            $retval .= '    <td colspan="5" align="center">SIN REGISTROS DISPONIBLES</td>';
            $retval .= '</tr>';
            
        }
        
        $retval .= '            </tbody>
                            </table>';

        $retval .= '    </div>';
        
        #   Presupuesto y Financiamiento
        $retval .= '    <div id="content">';
        $retval .= '        <h1>6. PRESUPUESTO Y FINANCIAMIENTO</h1>';
        $retval .= '    </div>';
        
        #   Plazo
        $retval .= '    <div id="content">';
        $retval .= '        <h1>7. PLAZO (D&Iacute;AS)</h1>';
        $retval .= '    </div>';
        
        #   Desemboso
        $retval .= '    <div id="content">';
        $retval .= '        <h1>8. FORMA DEL DESEMBOLSO</h1>';
        $retval .= '    </div>';
        
        #   Seguimiento
        $retval .= '    <div id="content">';
        $retval .= '        <h1>9. CRONOGRAMA MENSUAL VALORADO SEG&Uacute;N ACTIVIDAD DEL PROYECTO</h1>';
        $retval .= '    </div>';
        
        #   Concluciones
        $retval .= '    <div id="content">';
        $retval .= '        <h1>10. CONCLUSIONES</h1>';
        $retval .= '    </div>';
        
        #   RECOMENDACIONES
        $retval .= '    <div id="content">';
        $retval .= '        <h1>11. RECOMENDACIONES</h1>';
        $retval .= '    </div>';
        
        #   Firmas de Responsabilidad
        $retval .= '    <table class="change_order_items">
                            <tr>
                                <td align="center" width="50%" >
                                    <h2>Elaborado por,</h2>
                                </td>
                                <td align="center">
                                    <h2>Revisado y aprobado por,</h2>
                                </td>
                            </tr>
                        </table>';
        
        
        $retval .= ' </div>';

        return $retval;
    }

    /**
     * Retorno al panel de control
     */
    public function panelControl()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=proyectos', false ) );
    }
}