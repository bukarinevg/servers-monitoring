<?php 

namespace app\source\db;

use HaydenPierce\ClassFinder\ClassFinder;

class DataBaseFactory{

    /**
     * The configuration array.
     *
     * @var array
     */
    private array $config;

    /**
     * DataBaseFactory constructor.
     *
     * @param array $config The configuration array.
     */
    public static function getConnection(array $config): DBConnectionInterface{
        $classes = ClassFinder::getClassesInNamespace('app\source\db\connectors');
        foreach ($classes as $class) {
            if (self::doesDatabaseTypeMatch($class, $config['driver'])) {
                unset($config['driver']);
                return new $class(...array_values($config));
            }
        }
        throw new \Exception('No database connection class found for the given driver.');
    }

    /**
     * Checks if the DATABASE_TYPE constant in a class matches a given type.
     *
     * @param string $className The name of the class to check.
     * @param string $type The type to match.
     * @return bool True if the DATABASE_TYPE constant matches the type, false otherwise.
     * @throws ReflectionException If the class does not exist.
     */

    public static function doesDatabaseTypeMatch(string $className, string $type): bool {
        $reflection = new \ReflectionClass($className);
        if ($reflection->hasConstant('DATABASE_TYPE')) {
            return $reflection->getConstant('DATABASE_TYPE') === $type;
        }
        return false;
    }


}