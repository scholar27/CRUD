<?php declare(strict_types=1);
namespace Zsamme\Project;

use Zsamme\DatabaseConfig\databaseConfig;
use Zsamme\dataCollection\dataCollection;
use Zsamme\edit\edit;
use Zsamme\Register\logout;
use Zsamme\Register\register;
use Zsamme\login\login;
use Zsamme\skills\skillmatrix;
use Zsamme\SqlFactory\sqlFactory;
use Zsamme\Validator\validator;


class project extends dataCollection
{
    private databaseConfig $databaseConnection;
    private validator $validator;
    private sqlFactory $sqlFactory;
    
    public function __construct()
    {
        $this->databaseConnection = new databaseConfig();
        $this->validator = new validator();
        $this->sqlFactory = new sqlFactory();
        $this->getSite();
    }
    
    public function getSite() {
        $site = $_GET['site'] ?? "";
      
        $state = null;
        switch ($site) {
            case 'register':
                $state = new register($this->validator, $this->databaseConnection, $this->sqlFactory);
                require_once 'template/forms/registerForm.php';
                break;
            case 'login':
                $state = new login($this->validator, $this->databaseConnection, $this->sqlFactory);
                require_once 'template/forms/loginForm.php';
                break;
            case 'logout':
                $state = new logout();
                require_once 'template/startseite.html';
                break;
            case 'edit':
                session_start();
                if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                    header('location: index.php?site=login');
                    exit;
                } else {
                    $email = $_SESSION['email'];
                    $state = new edit($email, $this->validator, $this->databaseConnection, $this->sqlFactory);
                    require_once 'template/forms/editForm.php';
                }
                break;
            case 'skillmatrix':
                session_start();
                if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
                    header('location: index.php?site=login');
                    exit;
                } else {
                    $email = $_SESSION['email'];
                    $state = new skillmatrix($this->validator, $this->databaseConnection, $this->sqlFactory);
                    require_once 'template/skillmatrixForm.php';
                }
                break;

            default:
                require_once 'template/startseite.html';
                break;
        }
    }
}