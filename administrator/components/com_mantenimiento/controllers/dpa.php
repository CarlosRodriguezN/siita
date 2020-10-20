<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * 
 *  Controlador proyecto
 * 
 */
class mantenimientoControllerDpa extends JControllerForm
{
    protected $view_list = 'dpas';
    
    public function cargaDatos()
    {
        //  Instancio el modelo
        $model = $this->getModel();
        
        //  Instancio la tabla
        $table = $model->getTable();
        
        //  Cargo Zonas a la tabla relacion unidad territorial
        $table->loadZonas();
 
        //  Cargo las provincias de acuerdo de las Zonas 1 a la 7
        $table->loadZonasProvincias();

        //  Cargo Regiones
        $table->loadRegiones();
        
        //  Cargo Provincias Region
        $table->loadRegionProvincia();

        //  Cargo los cantones pertenecientes a determinadas provincias
        $table->loadProvinciasCantones();

        //  Cargo las cantones de las Zonas 8 y 9
        $table->loadZonasCantones();

        //  Cargo las Parroquias pertenecientes a determinados Cantones
        $table->loadCantonesParroquias();
        
        //  Cargo Distritos
        $table->loadDistritos();

        //  Cargo la relacion Distritos - Cantones
        $table->loadDistritosCantones();

        //  Cargo la relacion Distritos - Parroquias
        $table->loadDistritosParroquias();

        //  Cargo los circuitos registrados en la dpa 2011
        $table->loadCircuitos();

        //  Cargo la relacion Circuitos Cantones
        //  $table->loadCircuitosCantones();
        
        //  Cargo la relacion Circuitos - Parroquias
        $table->loadCircuitosParroquias();

        echo 'Listoooo Circuitos !!!!!'; exit;
        
        exit;
    }

    
    public function makeTablesTrigger()
    {
        $mdDPA = $this->getModel();
        $mdDPA->crearTbsAuditoria();
    }
    
    public function makeTrigger()
    {
        $mdDPA = $this->getModel();
        $mdDPA->generarTrigger();
    }
}