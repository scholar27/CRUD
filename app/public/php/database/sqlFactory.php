<?php
declare(strict_types=1);

namespace Zsamme\SqlFactory;

class sqlFactory
{
    public function updateUser($inputArrayInvariableFields): string
    {
        if (!empty($inputArrayInvariableFields['newPassword']))
        {
            return 'UPDATE user SET
                email=:email,
                first_name=:firstName,
                last_name=:lastName,
                password=:password,
                department_id=(SELECT id FROM department WHERE label = :selectDepartment),
                position_id=(SELECT id FROM position WHERE label = :selectPosition),
                company_id=(SELECT id FROM company WHERE label = :selectCompany),
                start_date=:startDate,
                birthday=:birthday,
                birthplace_id=(SELECT id FROM town WHERE name = :birthplace),
                current_living_place_id=(SELECT id FROM town WHERE name = :currentPlace),
                children=:children
            WHERE email=:email';
        }
            return 'UPDATE user SET
                email=:email,
                first_name=:firstName,
                last_name=:lastName,
                department_id=(SELECT id FROM department WHERE label = :selectDepartment),
                position_id=(SELECT id FROM position WHERE label = :selectPosition),
                company_id=(SELECT id FROM company WHERE label = :selectCompany),
                start_date=:startDate,
                birthday=:birthday,
                birthplace_id=(SELECT id FROM town WHERE name = :birthplace),
                current_living_place_id=(SELECT id FROM town WHERE name = :currentPlace),
                children=:children
            WHERE email=:email';
        
    }

    public function createUser(): string
    {
        return 'INSERT INTO user (
                   email,
                   password,
                   first_name,
                   last_name,
                   department_id,
                   position_id,
                   company_id,
                   start_date)
                VALUES (
                        :email,
                        :password,
                        :firstName,
                        :lastName,
                        (SELECT id FROM department WHERE label = :selectDepartment),
                        (SELECT id FROM position WHERE label = :selectPosition),
                        (SELECT id FROM company WHERE label = :selectCompany),
                        :startDate
                        )';
    }

    public function deleteEntry($table,$bindParamName): string
    {
        return 'DELETE FROM ' . $table . ' WHERE ' . $bindParamName . '=:' . $bindParamName;
    }

    public function createSingleEntry($table): string
    {
        return 'INSERT INTO ' . $table . '(name) Values(:name)';
    }

    public function createEntries($table, $parameters): string
    {
        $fieldnames = '';
        $values = '';
        foreach ($parameters as $parameter)
        {
            if ($parameter === end($parameters))
            {
                $fieldnames .= $parameter;
                $values .= ':' . $parameter;
            } else
            {
                $fieldnames .= $parameter . ', ';
                $values .= ':' . $parameter . ', ';
            }
        }
        return 'INSERT INTO ' . $table . '(' . $fieldnames . ') Values(' . $values . ')';
    }

    public function getAllEntriesIfEmailExisting(): string
    {
        return 'SELECT * FROM user WHERE email = :email';
    }

    public function getUserName(): string
    {
        return 'SELECT first_name FROM user WHERE email = :email';
    }

    public function selectWithIdCondition($fieldName, $table): string
    {
        return 'SELECT ' . $fieldName . ' FROM ' . $table . ' WHERE id = :id';
    }

    public function selectWithUserIdCondition($fieldName, $table): string
    {
        return 'SELECT ' . $fieldName . ' FROM ' . $table . ' WHERE user_id = :id';
    }

    public function selectWithCondition($fieldName, $table, $condition): string
    {
        return 'SELECT * FROM ' . $table . ' WHERE ' . $fieldName . ' = :' . $fieldName;
    }
    
    public function getValues($table): string
    {
        return 'SELECT * FROM ' . $table;
    }

/*
    public function updateSkills($skillId, $memberId, $level): string
    {
        return 'INSERT INTO levels (
                   level,
                   user_id,
                   skills_id,
                )
                VALUES (
                        $level[0],
                
                        foreach ($memberId as $member){
                            $stmt -> execute([$value]);
                        },
                        foreach ($skillId as $skill){
                            $stmt -> execute([$value]);
                        },
                        
                
                                 )';
    }*/
}