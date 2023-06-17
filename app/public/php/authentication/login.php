<?php declare(strict_types=1);

namespace Zsamme\login;

use PDO;
use Zsamme\DatabaseConfig\databaseConfig;
use Zsamme\dataCollection\dataCollection;
use Zsamme\SqlFactory\sqlFactory;
use Zsamme\Validator\validator;


class login extends dataCollection
{
    private validator $Validator;
    private databaseConfig $Config;
    private sqlFactory $SqlFactory;
    
    public function __construct(
        validator $validator,
        databaseConfig $config,
        sqlFactory $sqlFactory
    ) {
        $this->Validator = $validator;
        $this->Config = $config;
        $this->SqlFactory = $sqlFactory;
        $this->checkForSubmit();
    }
    
    public function checkForSubmit()
    {
        $inputArray = array();
        $inputArrayVariable = array();
        $errorsArray = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->getInput($inputArray, $inputArrayVariable);
            
            session_start();
            $email = $inputArray['email'];
            $password = $inputArray['password'];
            
            //check
            $this->Validator->checkIfEmpty($email, 'email', $errorsArray);
            $this->Validator->checkIfEmpty($password, 'password', $errorsArray);
            $this->compareWithDatabase($email, $password, $errorsArray);
            
            $_SESSION['errorsArray'] = $errorsArray;
            $_SESSION['email'] = $email;
        }
    }
    
    public function compareWithDatabase(&$email, &$password, &$errorsArray)
    {
        if (!$errorsArray) {
            // Prepare a select statement
            $sql = $this->SqlFactory->getAllEntriesIfEmailExisting();
            
            $pdo = $this->Config->getPdo();
            
            if ($stmt = $pdo->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Check if email exists, if yes then verify password
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            $id = $row['id'];
                            //email needed for session
                            $email = $row['email'];
                            $hashed_password = $row['password'];
                            if (password_verify($password, $hashed_password)) {
                                
                                // Store data in session variables
                                $_SESSION['loggedin'] = true;
                                $_SESSION['id'] = $id;
                                $_SESSION['email'] = $email;
                                
                                // Redirect user to welcome page
                                header('location: template/welcome.php');
                            } else {
                                // Password is not valid, display a generic error message
                                $errorsArray['typo'] = 'Invalid email or password.';
                            }
                        }
                    } else {
                        // Username doesn't exist, display a generic error message
                        $errorsArray['typo'] = 'Invalid email or password.';
                    }
                } else {
                    echo 'Oops! Something went wrong. Please try again later.';
                }
                
                // Close statement
                unset($stmt);
            }
        }
        
        // Close connection
        unset($pdo);
    }
    
}