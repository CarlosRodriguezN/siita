<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );


if ( isset( $pdf ) ){

    $font = Font_Metrics::get_font( "verdana" );
    // If verdana isn't available, we'll use sans-serif.
    if ( !isset( $font ) ){
        Font_Metrics::get_font( "sans-serif" );
    }
    $size = 6;
    $color = array ( 0, 0, 0 );
    $text_height = Font_Metrics::get_font_height( $font, $size );

    $foot = $pdf->open_object();

    $w = $pdf->get_width();
    $h = $pdf->get_height();

    // Draw a line along the bottom
    $y = $h - 2 * $text_height - 24;
    $pdf->line( 16, $y, $w - 16, $y, $color, 1 );

    $y += $text_height;

    $text = "Job: 132-003";
    $pdf->text( 16, $y, $text, $font, $size, $color );

    $pdf->close_object();
    $pdf->add_object( $foot, "all" );

    global $initials;
    $initials = $pdf->open_object();

    // Add an initals box
    $text = "Initials:";
    $width = Font_Metrics::get_text_width( $text, $font, $size );
    $pdf->text( $w - 16 - $width - 38, $y, $text, $font, $size, $color );
    $pdf->rectangle( $w - 16 - 36, $y - 2, 36, $text_height + 4, array ( 0.5, 0.5, 0.5 ), 0.5 );


    $pdf->close_object();
    $pdf->add_object( $initials );

    // Mark the document as a duplicate
    $pdf->text( 110, $h - 240, "DUPLICATE", Font_Metrics::get_font( "verdana", "bold" ), 110, array ( 0.85, 0.85, 0.85 ), 0, 0, -52 );

    $text = "Page {PAGE_NUM} of {PAGE_COUNT}";

    // Center the text
    $width = Font_Metrics::get_text_width( "Page 1 of 2", $font, $size );
    $pdf->page_text( $w / 2 - $width / 2, $y, $text, $font, $size, $color );
}
?>

<body>

    <div id="header">
        <table>
            <tr>
                <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_ECORAE' );?> </td>
                <td style="text-align: right"> <?php echo JText::_( 'COM_REPORTES_SIITA' );?> </td>
            </tr>
        </table>
    </div>

    <div id="footer">
        <div class="page-number"></div>
    </div>

    <div id="body">
        <div id="section_header"><br></div>

        <div id="content">
            <div class="page" style="font-size: 7pt">
                <!-- Cabecera Datos Generales -->
                <table style="width: 100%; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 8pt;">
                    <tr>
                        <td> <strong> <?php echo JText::_( 'COM_REPORTES_DATOS_GENERALES' );?> </strong></td>
                    </tr>
                </table>

                <!-- Informacion Datos Generales -->
                <table class="change_order_items">
                    <tbody>

                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_NOMBRE_PROYECTO' );?> </td>
                            <td> <strong> <?php echo htmlentities( $this->_items->nombreProyecto );?> </strong> </td>
                        </tr>

                        <tr class="odd_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_CUP_PROYECTO' );?> </td>
                            <td> <strong> <?php echo $this->_items->cupProyecto;?> </strong> </td>
                        </tr>

                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_ESTADO_PROYECTO' );?> </td>
                            <td> <strong> <?php echo $this->_items->estado;?> </strong> </td>
                        </tr>

                        <tr class="odd_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_FASE_PROYECTO' );?> </td>
                            <td> <strong> <?php echo '';?> </strong> </td>
                        </tr>

                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_MONTO_PROYECTO' );?> </td>
                            <td> <strong> <?php echo '$ ' . number_format( $this->_items->montoEstimado, 2, ',', '.' );?> </strong> </td>
                        </tr>

                        <tr class="odd_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_FECHA_INICIO_PROYECTO' );?> </td>
                            <td> <strong> <?php echo $this->_items->fechaInicioEstimada;?> </strong> </td>
                        </tr>

                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_FECHA_FIN_PROYECTO' );?> </td>
                            <td> <strong> <?php echo $this->_items->fechaFinEstimada;?> </strong> </td>
                        </tr>

                        <tr class="odd_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_FECHA_MODIFICACION_PROYECTO' );?> </td>
                            <td> <strong> <?php echo $this->_items->fechaModificacion;?> </strong> </td>
                        </tr>

                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_COBERTURA_PROYECTO' );?> </td>
                            <td> <strong> <?php echo '';?> </strong> </td>
                        </tr>

                        <tr class="odd_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_UNIDAD_RESPONSABLE_PROYECTO' );?> </td>
                            <td> <strong> <?php echo htmlentities( $this->_items->nombreUnidadGestion );?> </strong> </td>
                        </tr>

                    </tbody>
                </table>

                <!-- Cabecera Informacion narrativa -->
                <table style="width: 100%; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 8pt;">
                    <tr>
                        <td> <strong> <?php echo JText::_( 'COM_REPORTES_INFORMACION_NARRATIVA' );?> </strong></td>
                    </tr>
                </table>

                <!-- Informacion Informacion narrativa -->
                <table class="change_order_items">
                    <tbody>
                        <tr class="even_row">
                            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_DESCRIPCION_PROYECTO' );?> </td>
                            <td> <strong> <?php echo htmlentities( $this->_items->descripcionProyecto );?> </strong> </td>
                        </tr>
                    </tbody>
                </table>

                <hr/>

                <div id="section_header"><br><br></div>

                <!-- Informacion Indicadores Economicos Sociales -->
                <?php if( count( $this->_items->indicadores->lstIndEconomicos ) ): ?>
                    <!-- Cabecera Indicadores Economicos Sociales -->
                    <table style="width: 100%; border-top: 1px solid black; border-bottom: 1px solid black; font-size: 8pt;">
                        <tr>
                            <td> <strong> <?php echo JText::_( 'COM_REPORTES_INDICADORES_ECONOMICOS' );?> </strong></td>
                        </tr>
                    </table>
                    
                    <br>
                    
                    <?php foreach ( $this->_items->indicadores->lstIndEconomicos as $indicador ):?>
                    <?php   $this->_dtaIndicador = $indicador; ?>
                    <?php   echo $this->loadTemplate( 'ficha_metodologica' ); ?>
                    <hr/>
                    <?php endforeach;?>
                <?php endif; ?>
            </div>

        </div>
    </div>


</body>