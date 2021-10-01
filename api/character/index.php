<?php

require_once '../../bin/Helpers.php';
require_once '../../bin/DbPeople.php';

$allowedHttpMethod = [ 'POST', 'PATCH', 'DELETE' ];

if (matchURI('character'))
{
    if (in_array($_SERVER['REQUEST_METHOD'], $allowedHttpMethod))
    {
        header('Content-Type: application/json');

        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'POST':
            {
                try
                {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);

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

                    $bind = [
                        'No'       => $data['No'],
                        'Id'       => $data['Id'],
                        'Name'     => $data['Name'],
                        'Gender'   => $data['Gender'],
                        'Birthday' => $data['Birthday'],
                        'Title'    => $data['Title'],
                        'Unit'     => $data['Unit'],
                        'Email'    => $data['Email'],
                        'Mobile'   => $data['Mobile'],
                        'Address'  => $data['Address']
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

                    if ($result)
                    {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                        echo json_encode([
                            'code' => '200',
                            'message' => 'OK'
                        ], 320);
                    }
                    else
                    {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                        echo json_encode([
                            'code' => '400',
                            'message' => 'Insert Fail'
                        ], 320);
                    }
                }
                catch (Throwable $ex)
                {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
                    echo json_encode([
                        'code' => (string) $ex->getCode(),
                        'status' => $ex->getMessage()
                    ], 320);
                }
                
                break;
            }

            case 'PATCH':
            {
                try
                {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);

                    $changes = [];
                    $bind = [];
                    foreach ($data as $key => $value)
                    {
                        if ($key !== 'No')
                        {
                            $changes[] = "{$key} = :{$key}";
                        }
                        $bind[$key] = $value;
                    }
                    $change = implode(', ', $changes);

                    if (count($bind) > 0)
                    {
                        $sql = "UPDATE Characters SET {$change} WHERE `No` = :No;";

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

                        if ($result)
                        {
                            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                            echo json_encode([
                                'code' => '200',
                                'message' => 'OK'
                            ], 320);
                        }
                        else
                        {
                            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                            echo json_encode([
                                'code' => '400',
                                'message' => 'Update Fail'
                            ], 320);
                        }
                    }
                    else
                    {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                        echo json_encode([
                            'code' => '200',
                            'message' => 'No data updated'
                        ], 320);
                    }
                }
                catch (Throwable $ex)
                {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
                    echo json_encode([
                        'code' => (string) $ex->getCode(),
                        'status' => $ex->getMessage()
                    ], 320);
                }
                
                break;
            }

            case 'DELETE':
            {
                try
                {
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);

                    $sql = "DELETE FROM Characters WHERE `No` = :No;";

                    $query = $dbConn->prepare($sql);
                    $query->bindParam('No', $data['No']);
                    $result = $query->execute();

                    if ($result)
                    {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                        echo json_encode([
                            'code' => '200',
                            'message' => 'OK'
                        ], 320);
                    }
                    else
                    {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                        echo json_encode([
                            'code' => '400',
                            'message' => 'Update Fail'
                        ], 320);
                    }
                }
                catch (Throwable $ex)
                {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
                    echo json_encode([
                        'code' => (string) $ex->getCode(),
                        'status' => $ex->getMessage()
                    ], 320);
                }
                
                break;
            }
        }
    }
    else
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    }
}
else
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}
