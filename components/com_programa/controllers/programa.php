<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controllerform library
jimport( 'joomla.application.component.controllerform' );
jimport( 'joomla.filesystem.file' );

//  Adjunto libreria para la creacion de archivos Excel
jimport( 'excel.PHPExcel' );

//  Adjunto libreria para la creacion de archivos Excel
jimport( 'word.PHPWord' );

//  Adjunto libreria para creacion de archivos PDF
require_once( JPATH_LIBRARIES . DS . 'dompdf' . DS . 'dompdf_config.inc.php' );

/**
 * 
 *  Controlador Programas
 * 
 */
class ProgramaControllerPrograma extends JControllerForm
{
    protected $view_list = 'programas';

    protected function allowAdd( $data = array( ) )
    {
        return parent::allowAdd( $data );
    }

    protected function allowEdit( $data = array( ), $key = 'id' )
    {
        $id = isset( $data[$key] ) ? $data[$key] : 0;
        if( !empty( $id ) ){
            $user = JFactory::getUser();
            return $user->authorise( "core.edit", "com_programa.message." . $id );
        }
    }

    function add()
    {
        parent::add();
    }

    function save( $key = null, $urlVar = null )
    {
        $dataFormulario = JRequest::getVar( "jform" );
        $info = $dataFormulario["dataPrograma"];
        
        //  Accedo al modelo del componente com_programa
        $modelo = $this->getModel();

        //  Guardo la información
        if( $modelo->saveFromJSON( $info ) ) {

            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );

        }
    }

    function saveFiles()
    {
        $modelo = $this->getModel();
        
        if( $_FILES ) {//    Si hay archivos para guardar.
            $idPrograma = jRequest::getVar( 'idPrograma' );
            return $modelo->saveUploadFiles( $idPrograma );
        } else {
            return false;
        }
    }

    /**
     * Funcion que permite eliminar un programa.
     * 
     */
    function delete()
    {
        $proy = JRequest::getVar( 'intCodigo_prg' );
        $modelo = $this->getModel();
        $flag = $modelo->delPrograma( $proy );
        if( $flag ) {
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        } else {
            $this->setError( JText::_( 'COM_PROGRAMA_DONT_DEL_PROGRAMA' ) );
            $this->setMessage( $this->getError(), 'error' );

            $this->setRedirect( JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_list
                            . $this->getRedirectToListAppend(), false
                    )
            );
        }
    }

    /**
     * permite descargar un archivo en pdf
     */
    function pdf()
    {
        $idPrograma = JRequest::getVar( 'intCodigo_prg' );

        $this->_setDataReporte( $idPrograma );
    }

    public function _setDataReporte( $idPrograma )
    {
        $mdPrograma = $this->getModel( 'Programa', 'ProgramaModel' );
        $dataPrograma = $mdPrograma->getDataPrograma( $idPrograma );
        //  Instancio la clase DOMPDF, la cual gestiona la creacion de un archivo PDF
        $htmlPDF = $this->_getHTMLReportePDF( $dataPrograma );
        $dompdf = new DOMPDF();
        $dompdf->set_paper( 'A4', 'portrait' );

        $dompdf->load_html( $htmlPDF );
        $dompdf->render();
        $dompdf->stream( $dataPrograma['data']->strNombre_prg . '.pdf' );
        exit;
    }

    private function _getHTMLReportePDF( $data )
    {
        $retval = '<link rel="stylesheet" href="' . JURI::root() . 'libraries/dompdf/www/test/css/print_static.css' . '" type="text/css" />';
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
                                    <td colspan="5">Titulo: <strong>' . utf8_decode( $this->_dataProyecto[0]->nomProyecto ) . '</strong></td>
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


        $retval .= '                        </tbody>
                                        </table>
                                    </td>
                                </tr>';

        $retval .= '            <tr>
                                    <td colspan="5">Aporte de ECORAE: <strong>$ ' . number_format( $this->_dataProyecto[0]->monto, 2, ',', '.' ) . ' Usd. </strong></td>
                                </tr>';

        $retval .= '            <tr>
                                    <td colspan="5">Duraci&oacute;n: <strong>' . $this->_dataProyecto[0]->duracion . ' - ' . $this->_dataProyecto[0]->undMedida . '</strong></td>
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
    
    
    public function panel()
    {
        $this->setRedirect( JRoute::_( 'index.php?option=com_panel&view=programas', false ) );
    }
    
    /**
     *  Permite cerrar secion en el sistema 
     */
    public function cerrarSesion()
    {
        JSession::checkToken( 'request' ) or jexit( JText::_( 'JInvalid_Token' ) );

        $app = JFactory::getApplication();

        // Perform the log in.
        $error = $app->logout();

        // Check if the log out succeeded.
        if( !($error instanceof Exception) ){
            // Get the return url from the request and validate that it is internal.
            $return = JRequest::getVar( 'return', '', 'method', 'base64' );
            $return = base64_decode( $return );
            if( !JURI::isInternal( $return ) ){
                $return = '';
            }

            // Redirect the user.
            $app->redirect( JRoute::_( $return, false ) );
        } else{
            $app->redirect( JRoute::_( 'index.php?option=com_users&view=login', false ) );
        }
    }
    
    
}