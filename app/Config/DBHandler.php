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
}
