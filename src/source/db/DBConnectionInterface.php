<?php
namespace app\source\db; 
use PDO;
use PDOException;
/**
 * Constructs an SQL delete query.
 *
 * @param string $table The name of the table to delete from.
 * @param array $condition The condition(s) that must be met for a row to be deleted.
 * @return string The constructed SQL query.
 */
interface DBConnectionInterface{

    /**
     * The type of database.
     */
    const DATABASE_TYPE = '';
    /**
     * Establishes a connection to the database.
     *
     * @return  PDO|PDOException The database connection object.
     */
    public function getConnection() : PDO | PDOException;
}
?>