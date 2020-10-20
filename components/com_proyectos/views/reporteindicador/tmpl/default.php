<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

print ' <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
            <HTML>';

print $this->loadTemplate( 'head' );
print $this->loadTemplate( 'body' );

print '     </HTML>';