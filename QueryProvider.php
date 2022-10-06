<?php
/**
 **by Alireza Negahdari Khorsandfard
 ** All right reserved
 **/
require_once('DatabaseConnection.php');
class QueryProvider extends DatabaseConnection
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $insertQuery
     * @param array<string,mixed> $arrayBindKeyValue
     * @return null|int
     * $query example: 'INSERT INTO users (name,email,password) VALUES (:name,:email,:password)' 
     * arrayBindKeyValue Example [':name' => 'some new name' , ':email' => 'some@me.com , :password =>'si§d8x'] 
     * success : return last insertd id 
     * you can call affectedRows() to get how many rows inserted
     * error: $this->error
     */
    public function insertQuery(string $insertQuery, array $arrayBindKeyValue = []): int|null
    {
        $result = null;
        if ($this->executeQuery($insertQuery, $arrayBindKeyValue)) {
            $result = (int)$this->lastInsertId();
        }
        $this->secure();
        return $result;
    }

    /**
     * @param string $selectQuery
     * @param array<mixed> $arrayBindKeyValue
     * @return array<mixed>|null
     * $query example: 'SELECT * FROM users WHERE email = :email' 
     * arrayBindKeyValue Example [':email' => 'some@me.com'] 
     */
    public function selectQuery(string $selectQuery, array $arrayBindKeyValue = []): array|null
    {
        $result = null;
        if ($this->executeQuery($selectQuery, $arrayBindKeyValue)) {
            $result = $this->fetch();
        }
        $this->secure();
        return $result;
    }

    /**
     * @param string $updateQuery
     * @param array<string,mixed> $arrayBindKeyValue
     * @return null|int
     * $query example: 'UPDATE users SET name = :name , isActive = :isActive WHERE id = :id' 
     * arrayBindKeyValue Example [':name' => 'some new name' , ':isActive' => true , :id => 32 ] 
     * in success return positive number affected rows and in error null
     */
    public function updateQuery(string $updateQuery, array $arrayBindKeyValue = []): int|null
    {
        $result = null;
        if ($this->executeQuery($updateQuery, $arrayBindKeyValue))
        {
            $result =  $this->affectedRows();
        }
        $this->secure();
        return $result;
    }

    /**
     * @param string $deleteQuery
     * @param array<string,mixed> $arrayBindKeyValue
     * @return int|null
     * @query example: 'DELETE users SET name = :name , isActive = :isActive WHERE id = :id' 
     * @arrayBindKeyValue example [':id' => 32 ]
     * @success return positive number affected rows and in error null 
     */
    public function deleteQuery(string $deleteQuery, array $arrayBindKeyValue = []): int|null
    {
        $result = null;
        if ($this->executeQuery($deleteQuery, $arrayBindKeyValue))
        {
            $result =  $this->affectedRows();
        }
        $this->secure();
        return $result;
        
    }

    /**
     * @return string|null
     */
    public function getError():string|null
    {
        return $this->error;
    }

    /**
     * @param string $query
     * @param array<mixed> $arrayBind
     * @return bool
     * @success set this->affectedRows 
     * @error set this->error and return false
     * 
     */
    private function executeQuery(string $query, array $arrayBind): bool
    {
        $this->query($query);
        foreach ($arrayBind as $key => $value) {
            $this->bind($key, $value);
        }
        return $this->execute();
    }
}
