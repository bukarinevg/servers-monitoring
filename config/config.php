<?php 
return [
    "components" =>[
        'db' => [
            'host' => 'monitoring-db',
            'db_name' => 'monitoring',
            'username' => 'user',
            'password' => 'user',
            'driver' => 'mysql'	
        ],
        'email' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'bukarinevgeni@gmail.com',
            'password' => 'snno thio lmcd uqdh',
        ],    

    ],
    'admins' => [
        'bukarinevgeni@gmail.com',
        'asaf@bylith.com',
        'aviram@bylith.com',
    ]      
];