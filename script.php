<?php
require_once 'vendor/autoload.php';

use app\models\WebServerModel;
use app\source\db\DataBase;
use app\enums\ServerTypeEnum;
use app\source\services\Email;
use app\lib\checker\CheckerInterface;
use app\lib\checker\FtpServerChecker;
use app\lib\checker\HttpServerChecker;
use app\lib\checker\SshServerChecker;

class ServerCheckerStrategy {
    private ?CheckerInterface $checker;

    public function setChecker(CheckerInterface $checker): void {
        $this->checker = $checker;
    }

    public function checkServers(int $type): void {

        $ofset = 0;
        $limit = 100;
        while ($servers = WebServerModel::findByWithLimit(['type' => $type], $limit, $ofset)) {
            $this->checker->checkServers($servers);
            $ofset += $limit;
        }
    }
}

class ServerCheckerFactory {
    public static function createChecker(string $type, array $users = []): CheckerInterface | null {
        switch ($type) {
            case ServerTypeEnum::HTTP_SERVER->value:
                return new HttpServerChecker(
                    // servers :$servers, 
                    users: $users);
            case ServerTypeEnum::FTP_SERVER->value:
                return new FtpServerChecker(
                    // servers: $servers, 
                    users: $users);
            case ServerTypeEnum::SSH_SERVER->value:
                return new SshServerChecker(
                    // servers: $servers, 
                    users: $users
                );
            default:
                throw new InvalidArgumentException("Invalid server type");
        }
    }
}

$config = require_once 'config/config.php';
$db = DataBase::getInstance($config['components']['db']);
Email::getInstance($config['components']['email']);
$serverTypes = [
    ServerTypeEnum::HTTP_SERVER->value,
    ServerTypeEnum::FTP_SERVER->value,
    ServerTypeEnum::SSH_SERVER->value,
];

$serverChecker = new ServerCheckerStrategy();
foreach ($serverTypes as $serverType) {
    $checker = ServerCheckerFactory::createChecker($serverType, $config['admins'] ?? []);
    if ($checker) {
        $serverChecker->setChecker($checker);
        $serverChecker->checkServers($serverType);
    }
}