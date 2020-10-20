<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Cobertura Controller
 */
class ReporteControllerOrganigramas extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_PROYECTOS_PROYECTO';
    
    private $_dataProyecto;
    private $_dataIndFinancieros;
    private $_dataIndBeneficiarios;
    private $_dataIndGAP;
    private $_dataOtrosIndicadores;
    private $_dataUndTerritorial;
    private $_dataObjetivos;
    
    public function getModel( $name = 'Proyecto', $prefix = 'ProyectosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
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
     *  Gestiona la descarga de un archivo en formato Excel
     */
    public function expFileToExcel()
    {
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                    ->setLastModifiedBy("Maarten Balliauw")
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Office 2007 XLSX Test Document")
                                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Proyecto')
                    ->setCellValue('B2', 'ESPOCH')
                    ->setCellValue('C1', 'ECORAE')
                    ->setCellValue('D2', '2012');

        // Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'Miscellaneous glyphs')
                    ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /**
     *  Gestiona la descarga de un archivo en formato Word ( doc )
     */
    public function expFileToWord()
    {
        //  New Word Document
        $PHPWord = new PHPWord();

        //  New portrait section
        $section = $PHPWord->createSection();

        //  Add text elements
        $section->addText('Hello Mundo - ESPOCH - ECORAE!!!!!!!!');
        $section->addTextBreak(2);

        $section->addText('I am inline styled.', array('name'=>'Verdana', 'color'=>'006699'));
        $section->addTextBreak(2);

        $PHPWord->addFontStyle('Vamossssssss', array('bold'=>true, 'italic'=>true, 'size'=>16));
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        $section->addText('Cosassssssssssssssss.', 'rStyle', 'pStyle');
        $section->addText('de la vidaaaaaaaaaaaaaaaaaa', null, 'pStyle');

        //  Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-word');
        header('Content-Disposition: attachment;filename="testFuncionalidad001.doc"');
        header('Cache-Control: max-age=0');
        
        //  Output the file to the browser
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('php://output');
        
        exit;
    }
}