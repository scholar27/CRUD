<?php

namespace Zsamme\skills;

use DOMDocument;
use Zsamme\DatabaseConfig\databaseConfig;
use Zsamme\dataCollection\dataCollection;
use Zsamme\SqlFactory\sqlFactory;
use Zsamme\Validator\validator;

class skillmatrix extends dataCollection
{
    private validator $Validator;
    private sqlFactory $SqlFactory;
    private databaseConfig $Config;

    public function __construct(
        validator      $validator,
        databaseConfig $config,
        sqlFactory     $sqlFactory
    )
    {
        $this->Validator = $validator;
        $this->Config = $config;
        $this->SqlFactory = $sqlFactory;
        $values = $this->getKeyValuePairs('skills', 'name', 'id', $this->SqlFactory, $this->Config);

        $names = $this->getKeyValuePairs('user', 'first_name', 'id', $this->SqlFactory, $this->Config);
        $levels = $this->getCompleteTable('level', $this->SqlFactory, $this->Config);
        session_start();
        $_SESSION['values'] = $values;
        $_SESSION['names'] = $names;
        $_SESSION['levels'] = $levels;

        /*$data = json_decode(file_get_contents('php://input'), true);
        $name = $data["name"];
        $level = $data["level"];
        $skill = $data["skill"];

        var_dump($data);*/
    }


}