<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );

print ' <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
            <HTML>';

print $this->loadTemplate( 'head' );
print $this->loadTemplate( 'body' );

print '     </HTML>';
