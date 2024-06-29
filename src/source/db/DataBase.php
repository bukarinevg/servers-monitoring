<?php
namespace app\source\db;

use app\source\db\connectors\MySQLConnection;
use app\source\db\connectors\PostgreSQLConnection;
use app\source\db\connectors\MSSQLConnection;
use app\source\db\DataBaseFactory;
use app\source\SingletonTrait;
use PDO;

/**
 * Class DataBase
 *
 * This class is responsible for establishing a connection to a database based on the provided configuration.
 * It supports multiple database drivers.
 */
class DataBase  {

    use QueryBuilderTrait {
        select as traitSelect;
		insert as traitInsert;
        update as traitUpdate;
        delete as traitDelete;
	}

    use SingletonTrait;

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

    /**
     * Establishes a connection to the database based on the provided configuration.
     */
    private function connect(): bool {
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
    private function setDataBaseConnection(DBConnectionInterface $connection): PDO {
        return $this->db = $connection->getConnection() ??  throw new \PDOException("Error connecting to the database.");
        
    }

    /**
     * Selects data from the specified table based on the given columns and condition.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to select.
     * @param array $condition The condition for selection.
     * @return array The selected data.
     */
    public function select(string $table, array | string $columns, array $condition = []): array {
        $query = $this->traitSelect($table , $columns, $condition);
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Inserts data into the specified table.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to insert data into.
     * @param array $values The values to insert.
     * @return bool Returns true if the data is valid and inserted, otherwise throws an PDOException.
     */
    public function insert(string $table, array $columns, array $requestDictionary): int{
        $query = $this->traitInsert($table , $columns);
        $query = $this->db->prepare($query);
        $res = $query->execute($requestDictionary) ? true : throw new \PDOException("Error inserting data into the database.");
        return $this->db->lastInsertId();

    } 

    /**
     * Updates data in the specified table based on the given columns and condition.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to update.
     * @param array $values The values to update.
     * @param array $where The where clause for the update.
     * @return bool Returns true if the data is valid and updated, otherwise throws an PDOException.
     */
    public function update(string $table, array $columns, array $requestDictionary, array $where): bool{
        $query = $this->traitUpdate($table , $columns, $where);
        $query = $this->db->prepare($query);
        return $query->execute($requestDictionary) ? true : throw new \PDOException("Error updating data in the database.");
    }


    /**
     * Deletes data from the specified table based on the given condition.
     *
     * @param string $table The name of the table.
     * @param array $condition The condition for deletion.
     * @return bool Returns true if the data is valid and deleted, otherwise throws an PDOException.
     */
    public function delete(string $table, array $condition): bool{
        $query = $this->traitDelete($table , $condition);
        $query = $this->db->prepare($query);
        return $query->execute() ? true : throw new \PDOException("Error deleting data from the database.");
    }

    

}
  
