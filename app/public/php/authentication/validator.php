<?php declare(strict_types=1);

namespace Zsamme\Validator;

use PDO;

class validator
{
    
    public function checkIfEmpty($input, string $type, array &$errors): bool
    {
        if (empty($input)) {
            $errors[$type] = $type . ' must not be empty';
            return true;
        } else {
            return false;
        }
    }
    
    public function validateLength($input, string $type, $length, &$errors): bool
    {
        if (strlen($input) < $length) {
            $errors[$type] = $input . ' must at least be ' . $length . ' characters long';
            return false;
        } else {
            return true;
        }
    }
    
    public function validateInput($input, string $type, $regex, array &$errors): bool
    {
        if (!preg_match($regex, $input)) {
            $errors[$type] = $type . ' can only contain letters';
            return false;
        } else {
            return true;
        }
    }
    
    public function checkRedundancyForUsername($email, $sqlFactory, $config, &$errorsArray): void
    {
        $sql = $sqlFactory->getAllEntriesIfEmailExisting();
        
        $pdo = $config->getPdo();
        
        if ($stmt = $pdo->prepare($sql)) {
            // Set parameters
            $param_email = $email;
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $errorsArray['email'] = 'This email is already taken.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    
}