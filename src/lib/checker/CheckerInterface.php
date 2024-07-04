<?php

namespace app\lib\checker;

interface CheckerInterface {
    function checkServers(): void;
    function handleResults(array $results): void;
}
