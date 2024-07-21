<?php


namespace app\source\db;

use PDO;
use PDOException;
use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\exceptions\NotFoundException;


abstract class ActiveRecord{

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $id = null;


    public DataBase $db;

    protected string $table;

    public function __construct() {
        $this->db =  DataBase::getInstance();  
       
    }

        /** 
     * Magic method to set the value of a property.
     * 
     * @param string $name The name of the property.
     * @param mixed $value The value of the property.
     */
    public function __set(string $name, mixed $value)
    {
        $this->fields[$name] = $value;
        
    }

    /**
     * Magic method to get the value of a property.
     * 
     * @param string $name The name of the property.
     * @return mixed The value of the property.
     */
    public function __get($name)
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * Insert a new record into the database table.
     * Then data will be inserted into the database table.
     *
     * @param array $columns The columns to insert data into.
     * @param array $values The values to be inserted.
     * @return bool Returns true if the data is valid and inserted, otherwise throws an exception.
     */
    public function insert(array $columns , array $values): int {
        $requestDictionary = array_combine($columns, $values);
        $id = $this->db->insert($this->table , $columns, $requestDictionary);
        return $id;    
    }


    /**
     * Update a record in the database table.
     * The data will be updated in the database table.
     *
     * @param array $columns The columns to update data in.
     * @param array $values The values to be updated.
     * @param array $where The where clause for the update.
     * @return bool Returns true if the data is valid and updated, otherwise throws an exception.
     */
    public function update(array $columns , array $values , array $where): bool{
        $requestDictionary = array_combine($columns, $values);
        $this->db->update($this->table , $columns, $requestDictionary, $where);
        return true;
    }

    
    public function delete(): bool {
        if($this->id) {
            $this->db->delete($this->table, ['id' => $this->id]);
            return true;
        }
        return false;
    }

    

    /**
     * Find a record by its ID.
     *
     * @param int $id The ID of the record to find.
     * @return static The model object.
     */
    public static function find(int $id): static {
        $model = new static();
        $result = $model->db->select($model->table, ['*'], ['id' => $id]);
        if(! $result){
            throw new NotFoundException('Record not found');
        }   

        foreach ($result[0] as $key => $value) {
            $model->$key = $value;
        }

        return $model ;
    }

    /**
     * Find all records in the database table.
     *
     * @return array The array of model objects.
     */
    public static function findAll(): array {
        $model = new static();
        $result = $model->db->select($model->table, ['*']);
        $models = [];
        foreach ($result as $row) {
            $model = new static();
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $models[] = $model;
        }
        return $models;
    }

    /**
     * Find records by a condition.
     *
     * @param array $condition The condition to find records by.
     * @return array The array of model objects.
     */
    public static function findBy(array $condition): array {
        $model = new static();
        $result = $model->db->select($model->table, ['*'], $condition);
        $models = [];
        foreach ($result as $row) {
            $model = new static();
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $models[] = $model;
        }
        return $models;
    }


    /**
     * Find records by a condition with limit and offset.
     *
     * @param array $condition The condition to find records by.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The offset of the first record to return.
     * @return array The array of model objects.
     */

    public static function findByWithLimit(array $condition, int $limit, int $offset): array {
        $model = new static();
        $result = $model->db->selectWithLimit($model->table, ['*'], $condition, $limit, $offset);
        $models = [];
        foreach ($result as $row) {
            $model = new static();
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $models[] = $model;
        }
        return $models;
    }
     

} 