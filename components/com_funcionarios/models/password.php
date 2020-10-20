    <?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla modelform library
require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'funcionario.php';
jimport( 'joomla.application.component.modeladmin' );
JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_funcionarios' . DS . 'tables' );

/**
 * Modelo Plan Estratégico Institucional
 */
class FuncionariosModelPassword extends JModelAdmin
{

    private $testForm;

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable( $type = 'Usuario', $prefix = 'FuncionariosTable', $config = array( ) )
    {
        return JTable::getInstance( $type, $prefix, $config );
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		Data for the form.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	mixed	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm( $data = array( ), $loadData = true )
    {
        
        // Get the form.
        $form = $this->loadForm( 'com_funcionarios.password', 'password', array( 'control' => 'jform', 'load_data' => $loadData ) );
        $this->testForm = $form;

        if( empty( $form ) ) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState( 'com_funcionarios.password.password.data', array( ) );

        if( empty( $data ) ) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     *  Valida si la contraseña ingresada es igual a la de la base de datos
     * @return type
     */
    public function updPass()
    {
        $tbUser = $this->getTable( 'Usuario', 'FuncionariosTable' );
        
        $idUsr = json_decode( JRequest::getvar( 'id' ) );
        $password = json_decode( JRequest::getvar( 'password' ) );
        $newPass = json_decode( JRequest::getvar( 'newPass' ) );
        
        $actPass = $tbUser->getPassUsr( $idUsr );
        $result = new stdClass();
        if ( !empty($actPass) && $this->compararPassword( $password, $actPass ) ){
            $hash = $this->makeHashPassword($newPass);
            $result->data = $tbUser->updPassUsr( $idUsr, $hash );
        } else {
            $result->error = "Contraseña actual incorrecta";
        }
        
        return $result;
    }
    
    
    public function compararPassword( $password, $cryptPass )
    {
        $cryptPart = $porciones = explode(":", $cryptPass);
        $salt = $cryptPart[1];
        $crypt = md5($password.$salt);
        $hash = $crypt.':'.$salt; 
        $result = ($hash == $cryptPass) ? true : false;
        return $result;
    }
    
    
    /**
     *  Genera la sal para la contrasenia
     * @param type $length
     * @return string
     */
    public function makeSaltPass($length=8) {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $makepass = '';
        mt_srand(10000000*(double)microtime());
        for ($i = 0; $i < $length; $i++)
            $makepass .= $salt[mt_rand(0,61)];
        return $makepass;
    } 
    
    /**
     *  Genera el password para joomla
     * @param type $pass
     * @return string
     */
    public function makeHashPassword($pass)
    {
        // Salt and hash the password
        $salt = $this->makeSaltPass(32); 
        $crypt = md5($pass.$salt);
        $hash = $crypt.':'.$salt; 
        return $hash;
    } 
    
}