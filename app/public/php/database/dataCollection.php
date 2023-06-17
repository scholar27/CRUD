<?php

declare(strict_types=1);

namespace Zsamme\dataCollection;


class dataCollection
{
    private array $allowed_keys = [
        'email',
        'password',
        'oldPassword',
        'newPassword',
/*        'newConfirmPassword',*/
        'confirm_password',
        'firstName',
        'lastName',
        'company',
        'department',
        'position',
        'startDate',
        'birthday',
        'birthplace',
        'children',
        'currentPlace',
        'previousPlace',
        'hobby',
        'interest',
        'volunteering',
        'social_media'
    ];
    
    public function prepareSelects($sqlFactory, $config)
    {
        $departmentValues = $this->getDropdownValues('department', 'label', $sqlFactory, $config);
        $_SESSION['departmentValues'] = $departmentValues;
        $positionValues = $this->getDropdownValues('position', 'label', $sqlFactory, $config);
        $_SESSION['positionValues'] = $positionValues;
        $companyValues = $this->getDropdownValues('company', 'label', $sqlFactory, $config);
        $_SESSION['companyValues'] = $companyValues;
    }
    
    public function getInput(&$inputArray, &$inputArrayVariable)
    {
        $inputArrayInvariableFields = array_slice($_POST, 0, 14, true);
        $inputArrayVariableFields = array_slice($_POST, 14, null, true);
       
        //fill normal input array
        $this->sortPostIntoArray($inputArrayInvariableFields, $inputArray);
        
        //check for variable fields and fill corresponding array
        if (!empty($inputArrayVariableFields))
        {
            $this->sortPostIntoArray($inputArrayVariableFields, $inputArrayVariable);
        }
    }
    
    
    public function sortPostIntoArray($postArray, &$arrayToBeFilled)
    {
        foreach ($postArray as $key => $item)
        {
            //check for allowed keys
            if (in_array($key, $this->allowed_keys))
            {
                $$key = $key;
                if (gettype($item) === 'string')
                {
                    $arrayToBeFilled[$$key] = trim($item);
                } else
                {
                    $arrayToBeFilled[$$key] = $item;
                }
            }
        }
    }
    
    //todo umbenennen, da nicht nur dropdown values hier gewonnen werden können
    public function getDropdownValues($table, $fieldName, $sqlFactory, $config): array
    {
        $sql = $sqlFactory->getValues($table);
        $values = array();
        
        $pdo = $config->getPdo();
        
        if ($stmt = $pdo->query($sql))
        {
            while ($row = $stmt->fetch())
            {
                $values[] = ($row[$fieldName]);
            }
            // Close statement
            unset($stmt);
        } else
        {
            echo 'Oops, something went wrong';
        }
        return $values;
    }

    public function getKeyValuePairs($table, $fieldName, $fieldNameKey, $sqlFactory, $config): array
    {
        $sql = $sqlFactory->getValues($table);
        $values = array();

        $pdo = $config->getPdo();

        if ($stmt = $pdo->query($sql)) {
            while ($row = $stmt->fetch()) {
                $values[$row[$fieldNameKey]] = ($row[$fieldName]);
            }
            // Close statement
            unset($stmt);
        } else {
            echo 'Oops, something went wrong';
        }
        return $values;
    }

    public function getCompleteTable($table, $sqlFactory, $config): array
    {
        $sql = $sqlFactory->getValues($table);
        $levels = array();

        $pdo = $config->getPdo();

        if ($stmt = $pdo->query($sql)) {
            while ($row = $stmt->fetch()) { //zeile
                $levels[] = [$row['level'], $row['user_id'], $row['skills_id']];

            }
            // Close statement
            unset($stmt);
        } else {
            echo 'Oops, something went wrong';
        }

        return $levels;
    }
    
    
    public function prepareInput($input, $type, $regex, &$errorsArray, $validator)
    {
        if ($type === 'password')
        {
            $validator->validateLength($input, $type, 3, $errorsArray);
        }
        $validator->validateInput($input, $type, $regex, $errorsArray);
        $validator->checkIfEmpty($input, $type, $errorsArray);
    }
    
    public function prepareNullableInput($type, $input, &$errorsArray, $validator)
    {
        if (in_array($type, $this->allowed_keys))
        {
            //set default regex
            $regex = '/^[A-Za-z ]+$/';
            
            //in case 'children' regex must check for numbers not letters
            if ($type === 'children' || $type === 'birthday')
            {
                $regex = '/[\d-]+/';
            }
            $validator->validateInput($input, $type, $regex, $errorsArray);
        }
    }
    
    public function prepareEmail(&$email, &$errorsArray, $validator, $sqlFactory, $config)
    {
        $emailValid = $validator->validateInput(
            $email,
            'email',
            '/^[\w.%+-]+@[A-Za-z\d.-]+\.[A-Za-z]{2,}$/',
            $errorsArray
        );
        $emailEmpty = $validator->checkIfEmpty($email, 'email', $errorsArray);
        if (!$emailEmpty && $emailValid)
        {
            $validator->checkRedundancyForUsername(
                $email,
                $sqlFactory,
                $config,
                $errorsArray
            );
        }
    }
    
    public function prepareConfirmPassword($password, $confirm_password, &$errorsArray, $validator)
    {
        $passwordConfirmEmpty = $validator->checkIfEmpty($confirm_password, 'confirm_password', $errorsArray);
        if (!$passwordConfirmEmpty)
        {
            if (empty($errorsArray['newPassword']) && ($password != $confirm_password))
            {
                $errorsArray['confirm_password'] = 'Passwords did not match';
            }
        }
    }
}