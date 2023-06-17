<?php declare(strict_types=1);

namespace Zsamme\Register;

use Zsamme\DatabaseConfig\databaseConfig;
use PDO;
use Zsamme\dataCollection\dataCollection;
use Zsamme\SqlFactory\sqlFactory;
use Zsamme\Validator\validator;

/*require './php/database/dataCollection.php';*/

class register extends dataCollection
{
    private validator $Validator;
    private sqlFactory $SqlFactory;
    private databaseConfig $Config;
    
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
        session_start();
        $this->prepareSelects($this->SqlFactory, $this->Config);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
            $this->getInput($inputArray, $inputArrayVariable);
            //todo: die prepare funktionen in assignValidateMethode machen (diese Methode dann in Elternklasse)
            $this->prepareEmail($inputArray['email'], $errorsArray, $this->Validator, $this->SqlFactory,  $this->Config);
            $this->prepareInput($inputArray['password'], 'password', '/\w/', $errorsArray, $this->Validator);
            $this->prepareInput($inputArray['firstName'], 'firstName', '/^[A-Za-z]+$/', $errorsArray, $this->Validator);
            $this->prepareInput($inputArray['lastName'], 'lastName', '/^[A-Za-z]+$/', $errorsArray,$this->Validator);
            $this->prepareInput($inputArray['startDate'], 'startDate', '/[\d-]+/', $errorsArray, $this->Validator);
            $this->prepareConfirmPassword($inputArray['password'], $inputArray['confirm_password'], $errorsArray, $this->Validator);
            $this->insertIntoDatabase($inputArray, $errorsArray);
    
            $_SESSION['errorsArray'] = $errorsArray;
            $_SESSION['email'] = $inputArray['email'];
            $_SESSION['firstName'] = $inputArray['firstName'];
            $_SESSION['lastName'] = $inputArray['lastName'];
            $_SESSION['startDate'] = $inputArray['startDate'];
        }
    }
    
    public function insertIntoDatabase(&$inputArray, &$errorsArray)
    {
        if (!$errorsArray) {
            $sql = $this->SqlFactory->createUser();
            
            // Set parameters
            $param_password = password_hash($inputArray['password'], PASSWORD_DEFAULT); // Creates a password hash
          
            $pdo = $this->Config->getPdo();
            if ($stmt = $pdo->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(':email', $inputArray['email'], PDO::PARAM_STR);
                $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
                $stmt->bindParam(':firstName', $inputArray['firstName'], PDO::PARAM_STR);
                $stmt->bindParam(':lastName', $inputArray['lastName'], PDO::PARAM_STR);
                $stmt->bindParam(':selectDepartment', $inputArray['selectDepartment'], PDO::PARAM_STR);
                $stmt->bindParam(':selectPosition', $inputArray['selectPosition'], PDO::PARAM_STR);
                $stmt->bindParam(':selectCompany', $inputArray['selectCompany'], PDO::PARAM_STR);
                $stmt->bindParam(':startDate', $inputArray['startDate'], PDO::PARAM_STR);
                
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to login page
                    header('location: index.php?site=login');
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