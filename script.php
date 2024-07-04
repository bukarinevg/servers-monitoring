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

class ServerCheckerStategy {
    private ?CheckerInterface $checker;

    public function setChecker(CheckerInterface $checker): void {
        $this->checker = $checker;
    }

    public function checkServers(): void {
        $this->checker->checkServers();
    }
}



class ServerCheckerFactory {
    public static function createChecker(string $type, array $users = []): CheckerInterface {
        switch ($type) {
            case ServerTypeEnum::HTTP_SERVER->value:
                $servers = WebServerModel::findBy(['type' => $type]);
                return new HttpServerChecker(
                    servers :$servers, 
                    users: $users);
            case ServerTypeEnum::FTP_SERVER->value:
                $servers = WebServerModel::findBy(['type' => $type]);
                return new FtpServerChecker(
                    servers: $servers, 
                    users: $users);
            case ServerTypeEnum::SSH_SERVER->value:
                $servers = WebServerModel::findBy(['type' => $type]);
                return new SshServerChecker(
                    servers: $servers, 
                    users: $users
                );
            default:
                throw new InvalidArgumentException("Invalid server type");
        }
    }
}

$config = require_once 'config/config.php';
DataBase::getInstance($config['components']['db']);
Email::getInstance($config['components']['email']);


$serverChecker = new ServerCheckerStategy();

$httpChecker = ServerCheckerFactory::createChecker(ServerTypeEnum::HTTP_SERVER->value, $config['admins'] ?? []);
$serverChecker->setChecker($httpChecker);
$serverChecker->checkServers();

$ftpChecker = ServerCheckerFactory::createChecker(ServerTypeEnum::FTP_SERVER->value, $config['admins'] ?? []);
$serverChecker->setChecker($ftpChecker);
$serverChecker->checkServers();

$sshChecker = ServerCheckerFactory::createChecker(ServerTypeEnum::SSH_SERVER->value, $config['admins'] ?? []);
$serverChecker->setChecker($sshChecker);
$serverChecker->checkServers();