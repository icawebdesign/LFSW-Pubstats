<?php
/**
 * LFSWorld Pubstats Library
 *
 * @author Ian.H <ian.h@icawebdesign.co.uk>
 */
namespace Icawebdesign\LfswPubstats;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LfswPubstats
{
    public function log($message, $messageType = 'debug', array $context = [])
    {
        $messageTypeMapper = [
            'info'          => Logger::INFO,
            'alert'         => Logger::ALERT,
            'critical'      => Logger::CRITICAL,
            'warning'       => Logger::WARNING,
            'debug'         => Logger::DEBUG,
            'notice'        => Logger::NOTICE,
            'api'           => Logger::API,
            'emergency'     => Logger::EMERGENCY,
        ];

        if ((null === $messageType) || (!array_key_exists($messageType, $messageTypeMapper))) {
            $messageType = 'info';
        }

        $log = new Logger('lfswpubstats');
        $log->pushHandler(new StreamHandler(__DIR__ . '/../log/pubstats.log', $messageTypeMapper[$messageType]));
        $log->log($messageTypeMapper[$messageType], $message, $context);
    }
}
