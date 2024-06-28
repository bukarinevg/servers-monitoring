<?php 
namespace app\source\db\connectors;
use PDO;
use PDOException;
use app\source\db\AbstractDBConnection;
use app\source\db\DBConnectionInterface;
/**
 * Class MySQLConnection
 *
 * This class is responsible for establishing and managing a connection to a MySQL database.
 * It extends the AbstractDBConnection class and implements the DBConnectionInterface interface.
 */
class MySQLConnection  extends AbstractDBConnection implements DBConnectionInterface
{
    /**
     * The type of database.
     */
    const DATABASE_TYPE = 'mysql';
    /**
     * Establishes a connection to the database.
     *
     * @return PDO|PDOException The PDO connection object if successful, null otherwise.
     */
    #[\Override]
    public function getConnection(): PDO | PDOException
    {
        try {
            $connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            throw $e;
        }
    }
}

?>