<?php

namespace app\lib\checker;

interface CheckerInterface {
    function checkServers(array $servers): void;
    function handleResults(array $results): void;
}
