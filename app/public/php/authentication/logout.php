<?php declare(strict_types=1);

namespace Zsamme\Register;

class logout
{
    
    public function __construct()
    {
        $this->logout();
    }
    
    public function logout()
    {
        // Initialize the session
        session_start();
        
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session.
        session_destroy();
        
        // Redirect to login page
        header('location: template/startseite.html');
        exit;
    }
    
}