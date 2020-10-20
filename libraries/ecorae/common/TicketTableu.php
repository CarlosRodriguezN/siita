<?php

class TicketTableu
{
    private $_ipServidorTableau;
    private $_usuario;
    private $_sitio;
    private $_ticket;
    private $_url;
    private $_vista;
    private $_servidorTableu;

    public function __construct( $ipServidorTableau, $usuario, $sitio, $vista )
    {
        $this->_ipServidorTableau   = $ipServidorTableau;
        $this->_usuario             = $usuario;
        $this->_sitio               = $sitio;
        $this->_vista               = $vista;
    }

    private function generarTicket()
    {
        $dataTableu = array( 'username' => $this->_usuario, 'target_site' => $this->_sitio );
        $data = http_build_query( $dataTableu );

        $opts = array( 'http' => array( 'method' => 'POST',
                                        'header' => 'Content-type: application/x-www-form-urlencoded',
                                        'content' => $data ) );

        $context = stream_context_create($opts);
        
        $this->_servidorTableu = "http://" . $this->_ipServidorTableau . "/trusted";

        $resultado = file_get_contents( $this->_servidorTableu, false, $context );

        if( $resultado === false ){
            throw new Exception( "Hubo problemas al leer, $php_errormsg" );
        }

        $this->_ticket = $resultado;
        
        return;
    }

    private function generarUrl()
    {
        $this->_url = "{$this->_servidorTableu}/{$this->_ticket}/t/{$this->_sitio}/views/{$this->_vista}";
    }

    public function getUrl()
    {
        $this->generarTicket();
        $this->generarUrl();

        return $this->_url;
    }
}
