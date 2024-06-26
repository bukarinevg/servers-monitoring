<?php
namespace app\source\db;

use app\source\db\connectors\MySQLConnection;
use app\source\db\connectors\PostgreSQLConnection;
use app\source\db\connectors\MSSQLConnection;
use app\source\db\DataBaseFactory;
use PDO;

/**
 * Class DataBase
 *
 * This class is responsible for establishing a connection to a database based on the provided configuration.
 * It supports multiple database drivers.
 */
class DataBase  {

    use QueryBuilderTrait {
		insert as traitInsert;
	}

    /**
     * @var PDO  $db The database connection object.
     */    
    public PDO $db;

    /**
     * DataBase constructor.
     *
     * @param array $config The configuration array containing the database connection details.
     */
    public function __construct(#[\SensitiveParameter] private array  $config) {
        $this->connect();
    }
    
    public function insert($table, $columns, $requestDictionary): bool|\Exception{
        $query = $this->traitInsert($table , $columns);
        $query = $this->db->prepare($query);
        return $query->execute($requestDictionary) ? true : throw new \Exception("Error inserting data into the database.");

    } 

    /**
     * Establishes a connection to the database based on the provided configuration.
     */
    private function connect(): bool|\Exception {
        $dbArguments = [
            'host' => $this->config['host'],
            'driver' => $this->config['driver'],
            'db_name' => $this->config['db_name'],
            'username' => $this->config['username'],
            'password' => $this->config['password']
        ];
        $connection = DataBaseFactory::getConnection($dbArguments);
        $this->setDataBaseConnection($connection);
        return true;
    }

    /**
     * Sets the database connection object.
     *
     * @param DBConnectionInterface $DBConnectionInterface The database connection object.
     */
    private function setDataBaseConnection(DBConnectionInterface $connection): PDO|\Exception {
        return $this->db = $connection->getConnection() ??  throw new \Exception("Error connecting to the database.");
    }

}
  
