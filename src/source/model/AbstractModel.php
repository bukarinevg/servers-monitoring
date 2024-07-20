<?php 
namespace app\source\model;

use app\source\attribute\validation\AttributeValidationResource;
use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\attribute\AttributeHelper;
use app\source\http\RequestHandler;
use app\source\exceptions\BadRequestException;
use app\source\db\ActiveRecord;

/**
 * This is an abstract class that serves as the base for all models.
 */
 class AbstractModel extends ActiveRecord{
    


    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $created_at;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $updated_at;

    public array  $fields = [];


    
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
        parent::__construct();
        $this->fields = AttributeHelper::getFieldsWithAttribute($this::class, FieldAttribute::class); 
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
    public function toJson(array $attributes=[] ): string {
        $data = $this->getDataFromModel($attributes);
        return json_encode($data);
    }

    /**
     * Convert the model object to an array.
     *
     * @return array The array.
     */
    public function toArray(array $attributes = []): array {
        $data = $this->getDataFromModel($attributes);
        return $data;
    }
       
    private function getDataFromModel(array $attributes = []): array{
          
        $data = [];
        if($attributes) {
            foreach ($attributes as $attribute) {
                $data[$attribute] = $this->{$attribute};
            }
        }
        else {
            foreach ($this->fields as $property) {
                $data[$property] = $this->{$property};
            }
        }
        if(isset($data['created_at']))
            $data['created_at'] = date('H:i:s d-m-Y', $data['created_at']);
        if(isset($data['updated_at']))
            $data['updated_at'] = date('H:i:s d-m-Y', $data['updated_at']);
        return $data;
    }

    
}

