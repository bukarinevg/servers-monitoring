<?php 
namespace app\source\db\connectors;
use PDO;
use PDOException;
use app\source\db\AbstractDBConnection;
use app\source\db\DBConnectionInterface;

/**
 * Class PostgreSQLConnection
 *
 * This class is responsible for establishing and managing a connection to a PostgreSQL database.
 * It extends the AbstractDBConnection class and implements the DBConnectionInterface interface.
 */
class PostgreSQLConnection  extends AbstractDBConnection implements DBConnectionInterface
{
    /**
     * The type of database.
     */
    const DATABASE_TYPE = 'pgsql';
    /**
     * Establishes a connection to the PostgreSQL database.
     *
     * @return PDO|PDOException The PDO connection object if successful, null otherwise.
     */
    #[\Override]
    public function getConnection():  PDO | PDOException
    {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                'pssql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            throw $e;
        }

    }
}

?>