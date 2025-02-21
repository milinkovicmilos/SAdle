<?php

namespace App\Config;

class DBHandler
{
    protected readonly \PDO $db;

    public function __construct()
    {
        $envData = EnvReader::getEnvData();
        $dsn = "{$envData['DBTYPE']}:host={$envData['HOST']};dbname={$envData['DBNAME']}";

        $this->db = new \PDO($dsn, $envData['USERNAME'], $envData['PASSWORD']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public function query(string $query): array
    {
        return $this->db->query($query)->fetchAll();
    }

    public function selectAll(string $tableName): array
    {
        return $this->query("SELECT * FROM {$tableName}");
    }

    public function execPrepared(string $query, array $data): bool
    {
        return $this->db->prepare($query)->execute($data);
    }

    public function fetchPrepared(string $query, array $data): array
    {
        $stmt = $this->db->prepare($query);
        try {
            $stmt->execute($data);
        } catch (\PDOException $e) {
            echo $e;
        }
        return $stmt->fetchAll();
    }
}
