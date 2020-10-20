    <?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * unidad de gestion Controller
 */
class FuncionariosControllerFuncionarios extends JControllerAdmin
{
    //  Usamos para las cadenas de idioma
    protected $text_prefix = 'COM_FUNCIONARIOS_GESTION_FUNCIONARIOS';
    
    public function getModel( $name = 'Funcionario', $prefix = 'FuncionariosModel' ) 
    {
        $model = parent::getModel( $name, $prefix, array( 'ignore_request' => true ) );
        return $model;
    }
}