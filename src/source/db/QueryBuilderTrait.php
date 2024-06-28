<?php
namespace app\source\db;

/**
 * Trait QueryBuilderTrait
 *
 * This trait provides methods for building SQL queries.
 */
trait QueryBuilderTrait {
    /**
     * Inserts data into the specified table.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to insert data into.
     * @param array $values The values to insert.
     * @return string The SQL query.
     */
    public function insert(string $table, array $columns): string {
        // Implement the insert method here
        $colummsName = implode(', ', $columns);
        $columnsValues = array_map(function($column){
            return ':' . $column;
        }, $columns);
        $columnsValues = implode(', ', $columnsValues);
        return 
        'INSERT INTO ' . $table . 
        '(' . $colummsName . ')'.
        'VALUES (' .  $columnsValues . ')';
    }

    /**
     * Selects data from the specified table based on the given columns and condition.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to select.
     * @param array $condition The condition for selection.
     * @return string The SQL query.
     */
    public function select(string $table, array  $columns, array $condition = []): string {
        
        $conditionString = $this->buildCondition($condition);

        $columnStr = implode(', ', $columns);
        $columnStr = rtrim($columnStr, ', ');


        return 
            'SELECT ' .  $columnStr . 
            ' FROM ' . $table . 
            $conditionString;
    }


    /**
     * Updates data in the specified table based on the given columns and condition.
     *
     * @param string $table The name of the table.
     * @param array $columns The columns to update.
     * @param array $condition The condition for updating.
     * @return string The SQL query.
     */
    public function update(string $table, array $columns, array $condition = []): string {
        $conditionString = $this->buildCondition($condition);
        $setParts = [];
        foreach ($columns as $column => $value) {
            $setParts[] = $value . ' = :' . $value;
        }
        $setString = implode(', ', $setParts);
        
        return 
            'UPDATE ' . $table . 
            ' SET ' . $setString .
            $conditionString
            ;
    }
    /**
     * Deletes data from the specified table based on the given condition.
     *
     * @param string $table The name of the table.
     * @param array $condition The condition for deletion.
     * @return string The SQL query.
     */
    public function delete(string $table, array $condition = []): string {
        $conditionString = $this->buildCondition($condition);        
        return 
            'DELETE FROM ' . $table .  
            $conditionString;
        // Implement the delete method here
    }

    /**
     * Builds a condition string based on the given condition.
     *
     * @param array $condition The condition to build.
     * @return string The condition string.
     */
    protected function buildCondition(array $condition): string {
        $conditionString = '';
        foreach($condition as $key => $value){
            $conditionString = $key . ' = ' . $value . ' AND ';
        }
        $conditionString=  rtrim($conditionString, ' AND ');	
        $conditionString =  $conditionString ? ' WHERE ' . $conditionString : '';
        return $conditionString;
    }
}