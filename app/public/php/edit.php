<?php

declare(strict_types=1);

namespace Zsamme\edit;

use PDO;
use Zsamme\DatabaseConfig\databaseConfig;
use Zsamme\dataCollection\dataCollection;
use Zsamme\SqlFactory\sqlFactory;
use Zsamme\Validator\validator;

class edit extends dataCollection
{
    private validator $Validator;
    private sqlFactory $SqlFactory;
    private databaseConfig $Config;
    
    private array $tableValuePairs = [
        'previousPlace' => 'town',
        'hobby' => 'hobby',
        'interest' => 'interest',
        'volunteering' => 'volunteering',
        'social_media' => 'social_media'
    ];
    
    public function __construct(
        string $email,
        validator $validator,
        databaseConfig $config,
        sqlFactory $sqlFactory
    ) {
        $values = array();
        $this->Validator = $validator;
        $this->Config = $config;
        $this->SqlFactory = $sqlFactory;
        $this->getInformation($email, $values);
        $userId = intval($values['id']);
        $this->checkForSubmit($userId);
    }
    
    public function checkForSubmit($userId)
    {
        $inputArrayInvariableFields = array();
        $inputArrayVariableFields = array();
        
        $errorsArray = array();
        
        $this->prepareSelects($this->SqlFactory, $this->Config);
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->getInput($inputArrayInvariableFields, $inputArrayVariableFields);
            $this->assignValidateMethode($inputArrayInvariableFields, $errorsArray);
            $_SESSION['errorsArray'] = $errorsArray;
            $this->insertIntoDatabase($inputArrayInvariableFields, $inputArrayVariableFields, $errorsArray, $userId);
        }
    }
    
    public function assignValidateMethode(&$inputArrayInvariableFields, &$errorsArray)
    {
        foreach ($inputArrayInvariableFields as $key => $input)
        {
            switch ($key)
            {
                case 'email':
                    
                    $this->Validator->validateInput(
                        $inputArrayInvariableFields['email'],
                        'email',
                        '/^[\w.%+-]+@[A-Za-z\d.-]+\.[A-Za-z]{2,}$/',
                        $errorsArray
                    );
                    $this->Validator->checkIfEmpty($inputArrayInvariableFields['email'], 'email', $errorsArray);
                    break;
                
                case 'startDate':
                    
                    $this->prepareInput(
                        $inputArrayInvariableFields['startDate'],
                        'startDate',
                        '/[\d-]+/',
                        $errorsArray,
                        $this->Validator
                    );
                    break;
                
                case 'firstName':
                case 'lastName':
                    
                    $$key = $key;
                    $this->prepareInput($input, $$key, '/^[A-Za-z]+$/', $errorsArray, $this->Validator);
                    break;
                
                case 'newPassword':
                case 'oldPassword':
                case 'confirm_password':
                    
                    if (!empty($inputArrayInvariableFields['newPassword']))
                    {
                        $this->checkOldPassword(
                            $inputArrayInvariableFields['email'],
                            $inputArrayInvariableFields['oldPassword'],
                            $errorsArray
                        );
                        $this->prepareInput(
                            $inputArrayInvariableFields['newPassword'],
                            'password',
                            '/\w/',
                            $errorsArray,
                            $this->Validator
                        );
                        $this->prepareConfirmPassword(
                            $inputArrayInvariableFields['newPassword'],
                            $inputArrayInvariableFields['confirm_password'],
                            $errorsArray,
                            $this->Validator
                        );
                    }
                    break;
                
                case 'socialMedia':
                    
                    //todo implement
                    break;
                
                default:
                    if (!empty($input))
                    {
                        $this->prepareNullableInput($key, $input, $errorsArray, $this->Validator);
                    }
                    break;
            }
        }
    }
    
    
    public function checkOldPassword(&$email, &$password, &$errorsArray)
    {
        // Prepare a select statement
        $sql = $this->SqlFactory->getAllEntriesIfEmailExisting();
        
        $pdo = $this->Config->getPdo();
        
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                // Check if email exists, if yes then verify password
                if ($stmt->rowCount() == 1)
                {
                    if ($row = $stmt->fetch())
                    {
                        $hashed_password = $row['password'];
    
                        if (!password_verify($password, $hashed_password))
                        {
                            // Password is not valid, display an error message
                            $errorsArray['oldPassword'] = 'Old password not correct';
                        }
                    }
                } else
                {
                    // Username doesn't exist, display an error message
                    $errorsArray['email'] = 'Password passt nicht zu Email';
                }
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    public function getInformation($email, &$values)
    {
        //declare all possibly needed arrays
        $previousPlaceValues = array();
        $hobbyValues = array();
        $interestValues = array();
        $volunteeringValues = array();
        $socialMediaValues = array();
        
        //get data for non-optional fields
        $this->getUserInformation($email, $values);
        $userId = $values['id'];
        
        //get data or optional fields
        $this->getOptionalInformation($previousPlaceValues, $userId, 'town');
        $this->getOptionalInformation($hobbyValues, $userId, 'hobby');
        $this->getOptionalInformation($interestValues, $userId, 'interest');
        $this->getOptionalInformation($volunteeringValues, $userId, 'volunteering');
        $this->getSocialMediaValues($socialMediaValues, $userId, 'social_media');
        
        //summarise all variable input in one array
        $variableValues = [
            $previousPlaceValues,
            $hobbyValues,
            $interestValues,
            $volunteeringValues,
            $socialMediaValues
        ];
        
        //start session and hand over database values
        session_start();
        $_SESSION['values'] = $values;
        $_SESSION['previousPlaceValues'] = $previousPlaceValues;
        $_SESSION['hobbyValues'] = $hobbyValues;
        $_SESSION['interestValues'] = $interestValues;
        $_SESSION['volunteeringValues'] = $volunteeringValues;
        $_SESSION['socialMediaValues'] = $socialMediaValues;
        /*$_SESSION['variableValues'] = $variableValues;*/
    }
    
    public function getSocialMediaValues(&$socialMediaValues, $userId, $name)
    {
        $sql = $this->SqlFactory->selectWithUserIdCondition('url', $name);
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':id', $userId, PDO::PARAM_STR);
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                $fetch = $stmt->fetchAll();
                
                foreach ($fetch as $item)
                {
                    $socialMediaValues[] = $item[0];
                }
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    public function getOptionalInformation(&$variableValues, $userId, $name)
    {
        $fieldName = $name . '_id';
        $table = $name . '_mapping';
        $sql = $this->SqlFactory->selectWithUserIdCondition($fieldName, $table);
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':id', $userId, PDO::PARAM_STR);
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                $fetch = $stmt->fetchAll();
                
                foreach ($fetch as $item)
                {
                    $this->getCorrespondingValuesOfForeignKeys($name, $item[0], 'name');
                    $variableValues[] = $item[0];
                }
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    public function getUserInformation($email, &$values)
    {
        $sql = $this->SqlFactory->getAllEntriesIfEmailExisting();
        
        $pdo = $this->Config->getPdo();
        
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                $values = $stmt->fetch();
                unset($values['password']);
                unset($values[2]);
                $this->getCorrespondingValuesOfForeignKeys('company', $values['company_id'], 'label');
                $this->getCorrespondingValuesOfForeignKeys('department', $values['department_id'], 'label');
                $this->getCorrespondingValuesOfForeignKeys('position', $values['position_id'], 'label');
                $this->getCorrespondingValuesOfForeignKeys('town', $values['birthplace_id'], 'name');
                $this->getCorrespondingValuesOfForeignKeys('town', $values['current_living_place_id'], 'name');
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    public function getCorrespondingValuesOfForeignKeys($table, &$foreignKey, $fieldName)
    {
        $sql = $this->SqlFactory->selectWithIdCondition($fieldName, $table);
        
        $pdo = $this->Config->getPdo();
        
        $value = null;
        
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':id', $foreignKey, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                $value = $stmt->fetch()[$fieldName];
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        $foreignKey = $value;
    }
    
    //insert fields into tables that have an n:m relation to other tables
    public function insertVariableFieldsIntoOriginalDatabase($item, $table)
    {
        $sql = $this->SqlFactory->createSingleEntry($table);
        
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':name', $item, PDO::PARAM_STR);
            // Attempt to execute the prepared statement
            if (!$stmt->execute())
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    public function getId($item, $table)
    {
        $id = '';
        
        $sql = $this->SqlFactory->selectWithCondition('name', $table, '');
        
        $pdo = $this->Config->getPdo();
        
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':name', $item, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                // Check if item exists, if yes then verify password
                if ($stmt->rowCount() >= 1)
                {
                    if ($row = $stmt->fetch())
                    {
                        $id = $row['id'];
                    }
                }
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
        
        // Close connection
        unset($pdo);
        return $id;
    }
    
    public function delete($table, $bindParamName, $bindParamValue, $paramType)
    {
        $sql = $this->SqlFactory->deleteEntry($table, $bindParamName);
        
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':' . $bindParamName, $bindParamValue, $paramType);
            
            
            // Attempt to execute the prepared statement
            if (!$stmt->execute())
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    public function insertSocialMediaFieldsIntoDatabase($table, $item, $userId)
    {
        $parameters = ['user_id', 'url'];
        
        $sql = $this->SqlFactory->createEntries($table, $parameters);
        
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':url', $item, PDO::PARAM_STR);
            
            
            // Attempt to execute the prepared statement
            if (!$stmt->execute())
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    public function insertVariableFieldsIntoDatabase($item, $table, $userId)
    {
        //this is a mapping table with two ids, make sure to get all necessary ids
        $itemId = intval($this->getId($item, $table));
        $parameterField = $table . '_id';
        
        $parameters = ['user_id', $parameterField];
        $mappingTable = $table . '_mapping';
        
        $sql = $this->SqlFactory->createEntries($mappingTable, $parameters);
        
        $pdo = $this->Config->getPdo();
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':' . $parameterField, $itemId, PDO::PARAM_INT);
            
            
            // Attempt to execute the prepared statement
            if (!$stmt->execute())
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    public function compareStringFieldWithDatabase($table, $item)
    {
        //format
        $itemUc = ucfirst(strtolower($item));
        $sql = $this->SqlFactory->selectWithCondition('name', $table, $itemUc);
        
        $pdo = $this->Config->getPdo();
        
        if ($stmt = $pdo->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':name', $itemUc, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute())
            {
                // Check if item exists, if no then create
                if ($stmt->rowCount() === 0)
                {
                    $this->insertVariableFieldsIntoOriginalDatabase($itemUc, $table);
                }
            } else
            {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            // Close statement
            unset($stmt);
        }
        // Close connection
        unset($pdo);
    }
    
    
    public function prepareVariableFields($key, $arrayVariableField, $userId)
    {
        $table = $this->tableValuePairs[$key];
        $mappingTable = $table;
        if ($table != 'social_media')
        {
            $mappingTable = $table . '_mapping';
        }
        $this->delete($mappingTable, 'user_id', $userId, PDO::PARAM_INT);
        
        
        foreach ($arrayVariableField as $item)
        {
            if (!empty($item))
            {
                if ($table != 'social_media')
                {
                    $this->compareStringFieldWithDatabase($table, $item);
                    $this->insertVariableFieldsIntoDatabase($item, $table, $userId);
                } else
                {
                    $this->insertSocialMediaFieldsIntoDatabase($table, $item, $userId);
                }
            }
        }
    }
    
    public function insertIntoDatabase(&$inputArrayInvariableFields, &$inputArrayVariableFields, &$errorsArray, $userId)
    {
        //check for no errors
        if (!$errorsArray)
        {
            //check if variable fields are present
            if (!empty($inputArrayVariableFields))
            {
                //if yes, compare with database
                foreach ($inputArrayVariableFields as $key => $arrayVariableField)
                {
                    $this->prepareVariableFields($key, $arrayVariableField, $userId);
                }
            }
            /*foreach ($inputArrayInvariableFields as $key => $input)
            {
                $$key = $key;
            }*/
            
            $sql = $this->SqlFactory->updateUser($inputArrayInvariableFields);
            
          
            
            $pdo = $this->Config->getPdo();
            
            //make sure that town is always same format
            $birthplace = ucfirst(strtolower($inputArrayInvariableFields['birthplace']));
            $currentPlace = ucfirst(strtolower($inputArrayInvariableFields['currentPlace']));
            
            //check if towns are already in database
            if (!empty($inputArrayInvariableFields['birthplace']))
            {
                $this->compareStringFieldWithDatabase('town', $birthplace);
            }
            if (!empty($inputArrayInvariableFields['currentPlace']))
            {
                $this->compareStringFieldWithDatabase('town', $currentPlace);
            }
    
            //check if new password was set
            if (!empty($inputArrayInvariableFields['newPassword']))
            {
                $param_password = password_hash(
                    $inputArrayInvariableFields['newPassword'],
                    PASSWORD_DEFAULT
                ); // Creates a password hash
            }
            
            if ($stmt = $pdo->prepare($sql))
            {
                /*foreach ($inputArrayInvariableFields as $key => $input)
                {
                    $$key = $key;
                    $stmt->bindParam(':' . $$key, $input, PDO::PARAM_STR);
                }*/
                if (!empty($inputArrayInvariableFields['newPassword']))
                {
                    $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
                }
                
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(':email', $inputArrayInvariableFields['email'], PDO::PARAM_STR);
                $stmt->bindParam(':firstName', $inputArrayInvariableFields['firstName'], PDO::PARAM_STR);
                $stmt->bindParam(':lastName', $inputArrayInvariableFields['lastName'], PDO::PARAM_STR);
                $stmt->bindParam(':selectDepartment', $inputArrayInvariableFields['department'], PDO::PARAM_STR);
                $stmt->bindParam(':selectPosition', $inputArrayInvariableFields['position'], PDO::PARAM_STR);
                $stmt->bindParam(':selectCompany', $inputArrayInvariableFields['company'], PDO::PARAM_STR);
                $stmt->bindParam(':startDate', $inputArrayInvariableFields['startDate'], PDO::PARAM_STR);
                $stmt->bindParam(':birthday', $inputArrayInvariableFields['birthday'], PDO::PARAM_STR);
                $stmt->bindParam(':birthplace', $birthplace, PDO::PARAM_STR);
                $stmt->bindParam(':currentPlace', $currentPlace, PDO::PARAM_STR);
                $stmt->bindParam(':children', $inputArrayInvariableFields['children'], PDO::PARAM_INT);
                
                // Attempt to execute the prepared statement
                if ($stmt->execute())
                {
                    //todo Erfolgsmeldung anzeigen
                    // Redirect to login page
                    header('location: template/welcome.php');
                } else
                {
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