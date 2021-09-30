<?php

require_once 'DbPeople.php';

try
{
    $jsonFile = '../storage/json/characters.json';
    $json = file_get_contents($jsonFile);
    $data = json_decode($json, true);

    // var_dump($data);

    $sql = <<<SQL
    INSERT INTO Characters (
        `No`,
        Id,
        Name,
        Gender,
        Birthday,
        Title,
        Unit,
        Email,
        Mobile,
        Address
    )
    VALUES (
        :No,
        :Id,
        :Name,
        :Gender,
        :Birthday,
        :Title,
        :Unit,
        :Email,
        :Mobile,
        :Address
    );
    SQL;

    $dbConn->beginTransaction();

    foreach ($data as $person)
    {
        $bind = [
            'No'       => $person['no'],
            'Id'       => $person['id'],
            'Name'     => $person['name'],
            'Gender'   => $person['gender'] == '男' ? 1 : 0,
            'Birthday' => $person['birthday'],
            'Title'    => $person['title'],
            'Unit'     => $person['unit'],
            'Email'    => $person['email'],
            'Mobile'   => $person['mobile'],
            'Address'  => $person['address']
        ];

        $query = $dbConn->prepare($sql);
        foreach ($bind as $key => $value)
        {
            if ($key == 'Gender')
            {
                $query->bindParam($key, $bind[$key], PDO::PARAM_INT);
            }
            else
            {
                $query->bindParam($key, $bind[$key]);
            }
        }
        $result = $query->execute();
    }

    $dbConn->commit();
}
catch (Throwable $ex)
{
    $exType = get_class($ex);
    $exCode = $ex->getCode();
    $exMsg  = $ex->getMessage();
    echo "{$exType} ({$exCode}): {$exMsg}";
}
