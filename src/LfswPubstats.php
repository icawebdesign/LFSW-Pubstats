<?php
/**
 * This file is part of the icawebdesign/LFSW-Pubstats library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2006-2016. Ian.H <ian.h@icawebdesign.co.uk>
 * @license http://opensource.org/licenses/MIT MIT
 * @link https://github.com/icawebdesign/LFSW-Pubstats GitHub
 */



namespace Icawebdesign\LfswPubstats;

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LfswPubstats
 *
 * @package Icawebdesign\LfswPubstats
 */
class LfswPubstats
{
    /**
     * @var array
     */
    protected $config = [];
    /**
     * @var string
     */
    protected $dotenvFile = '.env';

    /**
     * LfswPubstats constructor.
     */
    public function __construct($dotenvFile = '.env')
    {
        $this->dotenvFile = $dotenvFile;
        $this->readConfig(include_once __DIR__ . '/config/config.php');

        return $this;
    }

    /**
     * Log info
     *
     * @param string $message
     * @param string $messageType
     * @param array  $context
     */
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

    /**
     * Read config from config.php file and .env
     *
     * @param $configFile
     *
     * @return array
     */
    public function readConfig($configFile)
    {
        // Store default config
        $this->config = $configFile;
        
        // Read .env file if it exists
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = new Dotenv(__DIR__ . '/..', $this->dotenvFile);
            $env = $dotenv->load();

            try {
                $dotenv->required('IDKEY')->notEmpty();
            } catch (\Exception $e) {
                die(trigger_error($e->getMessage(), E_USER_ERROR));
            }

            foreach ($env as $line) {
                if (0 === strpos(trim($line), '#')) {
                    // Skip comment lines
                    continue;
                }

                if (false !== strpos($line, '=')) {
                    list($key, $value) = array_map('trim', explode('=', $line, 2));

                    $this->config[$key] = str_replace(['export ', "'", '"'],  '', $value);
                }
            }
        }

        return $this->config;
    }

    /**
     * Get config data
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
