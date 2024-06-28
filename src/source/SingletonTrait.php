<?php 

namespace app\source;

trait SingletonTrait{

    final public static function getInstance(...$args) {

        static $instance = [];
        $called_class = get_called_class();
        

        if( !isset( $instance[ $called_class ] ) ) {
            $instance[$called_class] = new $called_class(...$args);
        }

        return $instance[ $called_class ];
    }
}
