<?php 
namespace app\source\model;

use app\source\attribute\validation\AttributeValidationResource;
use app\source\db\DataBase;
use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\attribute\AttributeHelper;
use app\source\http\RequestHandler;
use Exception;
use League\Container\Exception\NotFoundException;
use PDOException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * This is an abstract class that serves as the base for all models.
 */
abstract class AbstractModel {

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $id = null;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $created_at;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $updated_at;

    public array  $fields = [];

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
    public array $data = [];


    /**
     * Class AbstractModel
     * 
     * This class represents an abstract model.
     */
    public function __construct() {
        $this->db =  DataBase::getInstance();   
        $this->fields = AttributeHelper::getFieldsWithAttribute($this::class, FieldAttribute::class); 
    }


    /**
     * Insert a new record into the database table.
     * Then data will be inserted into the database table.
     *
     * @param array $columns The columns to insert data into.
     * @param array $values The values to be inserted.
     * @return bool Returns true if the data is valid and inserted, otherwise throws an exception.
     */
    public function insert(array $columns , array $values ): int {
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
     * @return AbstractModel The model object.
     */
    public static function find(int $id): AbstractModel {
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
     * Load the data from the request object.
     * Validates the data and sets the properties of the model.
     *
     * @param RequestHandler $request
     * @return void
     */
    public function load(RequestHandler $request): null {
        $this->data = $request->getContent();

        if(! $this->data) {
            throw new BadRequestException('Request is empty');
        }

        AttributeValidationResource::validateALLProperties($this::class, $this->data);

        foreach ($this->data as $key => $value) {
            $this->$key = $value;
        }

        // echo 'Data is valid';

        return null;
    }

    public function save(): void  {
        $columns = [];
        $values = [];

        if($this->id) {
            $this->updated_at = time();
        } else {
            $this->created_at = time();
        }

        foreach ($this->fields as $field) {  
            $columns[] = $field;
            $values[] = $this->$field ?? null;
        }

        if(empty($columns) || empty($values)) {
            throw new BadRequestException('No data to save');
        }

        if($this->id) {
            
            $this->update($columns, $values, ['id' => $this->id]);
        }
        else {
            $id = $this->insert($columns, $values);
            $model =  self::find($id);
            
            foreach ($this->fields as $field) {
                $this->$field = $model->$field;
            }
        }
    }

    /**
     * Convert the model object to a JSON string.
     *
     * @return string The JSON string.
     */
    public function toJson(): string {
        $data = [];

        foreach ($this->fields as $property) {
            $data[$property] = $this->{$property};
        }
        
        return json_encode($data);
    }
       

    
}

