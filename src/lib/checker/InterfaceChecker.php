<?php

namespace app\lib\checker;

interface InterfaceChecker {
    function checkServers(): void;
    function handleResults(array $results): void;
}
