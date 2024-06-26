<?php
namespace app\source\controller;

use app\source\App;

/**
 * This is an abstract class that serves as the base controller for all controllers in the application.
 */
abstract class AbstractController
{
    /**
     * @var object $app The application object.
     */
    /**
     * Constructor for the AbstractController class.
     *
     * @param mixed $app The application object.
     */
    public function __construct(protected App $app)
    {
        $this->app = $app;
    }
}
