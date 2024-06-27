<?php 
namespace app\source\model;

use app\source\attribute\validation\AttributeValidationResource;
use app\source\db\DataBase;
use app\source\db\QueryBuilderTrait;
use app\source\http\RequestHandler;
use Exception;

/**
 * This is an abstract class that serves as the base for all models.
 */
abstract class AbstractModel {

    /**
     * @var DataBase $db The PDO connection object.
     */
    protected DataBase $db;

    /**
     * The name of the database table for model.
     */
    public string $table;
    
    /**
     * The fields for model.
     */
    public array $fields;


    /**
     * Class AbstractModel
     * 
     * This class represents an abstract model.
     */
    public function __construct() {
        $config = require 'config/config.php';
        $this->db =  (new DataBase($config['components']['db']));
    }


    /**
     * Insert a new record into the database table.
     * Then data will be inserted into the database table.
     *
     * @param array $columns The columns to insert data into.
     * @param array $values The values to be inserted.
     * @return bool|\Exception Returns true if the data is valid and inserted, otherwise throws an exception.
     */
    public function insert(array $columns , array $values ): bool|\Exception {
        $requestDictionary = array_combine($columns, $values);
        $this->db->insert($this->table , $columns, $requestDictionary);
        return true;    
    }

    
    /**
     * Update a record in the database table.
     * The data will be updated in the database table.
     *
     * @param array $columns The columns to update data in.
     * @param array $values The values to be updated.
     * @param string $where The where clause for the update.
     * @return bool|\Exception Returns true if the data is valid and updated, otherwise throws an exception.
     */
    // public function update(array $columns , array $values , string $where): bool|\Exception {
    //     $requestDictionary = array_combine($columns, $values);
    //     $this->db->update($this->table , $columns, $requestDictionary, $where);
    //     return true;
    // }


    /**
     * Load the data from the request object.
     * Validates the data and sets the properties of the model.
     *
     * @param RequestHandler $request
     * @return void
     */
    public function load(RequestHandler $request): null |Exception{

        $data = $request->getContent();

        foreach($this->fields as $field){
            if(!isset($data[$field])){
                throw new \Exception("Field $field is not set");
            }
            if(AttributeValidationResource::validateProperty($this::class ,  $field, $data[$field])) {
                $this->$field = $data[$field];
            }

        }
        return null;
    }
}

