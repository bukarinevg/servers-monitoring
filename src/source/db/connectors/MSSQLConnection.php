<?php 
namespace app\source\db\connectors;
use PDO;
use PDOException;
use app\source\db\AbstractDBConnection;
use app\source\db\DBConnectionInterface;

/**
 * Class MSSQLConnection
 *
 * This class is responsible for establishing and managing a connection to a Microsoft SQL Server database.
 * It extends the AbstractDBConnection class and implements the DBConnectionInterface interface.
 */
class MSSQLConnection extends AbstractDBConnection implements DBConnectionInterface
{
    /**
     * The type of database.
     */
    const DATABASE_TYPE = 'mssql';

    /**
     * Establishes a connection to the database.
     *
     * @return PDO|null The PDO connection object if successful, null otherwise.
     */
    #[\Override]
    public function getConnection():  PDO | PDOException
    {
        $this->connection = null;
        try {
            $this->connection = new PDO(
                'sqlsrv:Server=' . $this->host . ';Database=' . $this->db_name,
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