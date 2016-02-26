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
     * @var string
     */
    protected $lfswUrl = '';

    const carCodes = [
        'XFG'           => 'XF GTi',
        'XRG'           => 'XR GT',
        'XRT'           => 'XR GT Turbo',
        'RB4'           => 'RB4 GT',
        'FXO'           => 'FXO Turbo',
        'LX4'           => 'LX4',
        'LX6'           => 'LX6',
        'MRT'           => 'MRT5',
        'UF1'           => 'UF1000',
        'RAC'           => 'RaceAbout',
        'FZ5'           => 'FZ50',
        'FOX'           => 'Formula XR',
        'XFR'           => 'XF GTR',
        'UFR'           => 'UF GTR',
        'FO8'           => 'Formula V8',
        'FXR'           => 'FXO GTR',
        'XRR'           => 'XR GTR',
        'FZR'           => 'FZ GTR',
        'BF1'           => 'BMW Sauber F1.06',
        'FBM'           => 'Formula BMW FB02',
    ];

    const carBits = [
        1               => 'XFG',
        2               => 'XRG',
        4               => 'XRT',
        8               => 'RB4',
        16              => 'FXO',
        32              => 'LX4',
        64              => 'LX6',
        128             => 'MRT',
        256             => 'UF1',
        512             => 'RAC',
        1024            => 'FZ5',
        2048            => 'FOX',
        4096            => 'XFR',
        8192            => 'UFR',
        16384           => 'FO8',
        32768           => 'FXR',
        65536           => 'XRR',
        131072          => 'FZR',
        262144          => 'BF1',
        524288          => 'FBM',
    ];

    const optionBits = [
        0               => 'WHEELSTEER',
        1               => 'LEFTHANDDRIVE',
        2               => 'GEARCHANGECUT',
        4               => 'GEARCHANGEBLIP',
        8               => 'AUTOGEAR',
        16              => NULL,
        32              => NULL,
        64              => 'BRAKEHELP',
        128             => 'THROTTLEHELP',
        256             => NULL,
        512             => NULL,
        1024            => 'MOUSESTEER',
        2048            => 'KEYBOARDSTEERHOHELP',
        4096            => 'KEYBOARDSTEERSTABILISED'
    ];

    const trackCodes = [
        '000'           => 'Blackwood GP',
        '001' 			=> 'Blackwood GP reversed',
        '010' 			=> 'Blackwood RallyX',
        '011' 			=> 'Blackwood RallyX reversed',
        '020' 			=> 'Blackwood Car Park',
        '100' 			=> 'South City Classic',
        '101' 			=> 'South City Classic reversed',
        '110' 			=> 'South City Sprint Track 1',
        '111' 			=> 'South City Sprint Track 1 reversed',
        '120' 			=> 'South City Sprint Track 2',
        '121' 			=> 'South City Sprint Track 2 reversed',
        '130' 			=> 'South City Long',
        '131' 			=> 'South City Long reversed',
        '140' 			=> 'South City Town Course',
        '141' 			=> 'South City Town Course reversed',
        '200' 			=> 'Fern Bay Club',
        '201' 			=> 'Fern Bay Club reversed',
        '210' 			=> 'Fern Bay Green',
        '211' 			=> 'Fern Bay Green reversed',
        '220' 			=> 'Fern Bay Gold',
        '221' 			=> 'Fern Bay Gold reversed',
        '230' 			=> 'Fern Bay Black',
        '231' 			=> 'Fern Bay Black reversed',
        '240' 			=> 'Fern Bay Rallycross',
        '241' 			=> 'Fern Bay Rallycross reversed',
        '250' 			=> 'Fern Bay Rally X Green',
        '251' 			=> 'Fern Bay Rally X Green reversed',
        '300' 			=> 'Autocross',
        '310' 			=> 'Autocross Skid pad',
        '320' 			=> 'Autocross Drag strip',
        '330' 			=> 'Autocross 8 lane drag strip',
        '400' 			=> 'Kyoto Ring Oval',
        '401' 			=> 'Kyoto Ring Oval reversed',
        '410' 			=> 'Kyoto Ring National',
        '411' 			=> 'Kyoto Ring National reversed',
        '420' 			=> 'Kyoto Ring GP Long',
        '421' 			=> 'Kyoto Ring GP Long reversed',
        '500' 			=> 'Westhill International',
        '501' 			=> 'Westhill International reversed',
        '600' 			=> 'Aston Cadet',
        '601' 			=> 'Aston Cadet reversed',
        '610' 			=> 'Aston Club',
        '611' 			=> 'Aston Club reversed',
        '620' 			=> 'Aston National',
        '621' 			=> 'Aston National reversed',
        '630' 			=> 'Aston Historic',
        '631' 			=> 'Aston Historic reversed',
        '640' 			=> 'Aston Grand prix',
        '641' 			=> 'Aston Grand prix reversed',
    ];

    const ruleCodes = [
        'V'             => 'Can Vote',
        'S'             => 'Can Select',
        'Q'             => 'Qualifying',
        'P'             => 'Private',
        'M'             => 'Modified',
    ];

    const ruleBits = [
        1               => 'Can Vote',
        2               => 'Can Select',
        4               => 'Qualifying',
        8               => 'Private',
        16              => 'Modified',
        32              => 'Mid-race Join',
        64              => 'Must Pit',
        128             => 'Can Reset',
        256             => 'Force Cockpit View',
        512             => 'Cruise',
    ];

    const serverTypes = [
        'S1 (old)',
        'S1',
        'S2',
        'S3',
    ];

    /**
     * LfswPubstats constructor.
     */
    public function __construct($dotenvFile = '.env')
    {
        $this->dotenvFile = $dotenvFile;
        $this->readConfig(include __DIR__ . '/config/config.php');

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
            $env = $dotenv->overload();

            try {
                $dotenv->required('IDKEY')->notEmpty();
            } catch (\Exception $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
                die();
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
     * @param mixed null|string $key
     * @return mixed string|array
     */
    public function getConfig($key = null)
    {
        if (null !== $key) {
            return $this->config[$key];
        }

        return $this->config;
    }

    /**
     * Retrieve env value from config
     *
     * @param      $key
     * @param null $default
     *
     * @return bool|null|string|void
     */
    public static function env($key, $default = null)
    {
        $value = getenv($key);

        if (false === $value) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if ((0 === strpos($value, '"')) && ((strlen($value) + 1) === strrpos($value, '"'))) {
            return substr($value, 1, -1);
        }
    }

    /**
     * Set LFSWorld URL with query string for action and params
     *
     * @param array $queryString
     *
     * @return $this
     */
    protected function setLfswUrl(array $queryString = [])
    {
        $this->lfswUrl = $this->getConfig('LFSW_URL') .
                         "?idk={$this->getConfig('IDKEY')}" .
                         '&s=1' .
                         '&' . http_build_query($queryString, null, '&');

        if (null !== $this->getConfig('API_VERSION')) {
            $this->lfswUrl .= "&version={$this->getConfig('API_VERSION')}";
        }

        return $this;
    }

    /**
     * Parse colour codes (^1) into CSS class names
     *
     * @param string $data
     *
     * @return string
     */
    protected function parseColourCodes($data)
    {
        // Return base data if no colour control char is found
        if (false === strpos($data, '^')) {
            return $data;
        }

        $colourMap = [
            '0'             => 'lfs-black',
            '1'             => 'lfs-red',
            '2'             => 'lfs-green',
            '3'             => 'lfs-yellow',
            '4'             => 'lfs-blue',
            '5'             => 'lfs-magenta',
            '6'             => 'lfs-cyan',
            '7'             => 'lfs-white',
            '8'             => 'lfs-default',
        ];

        $data = preg_replace_callback(
            '`\^([0-8])(.*?)(?=(?:\^[0-8])|\z)`',
            function($matches) use ($colourMap) {
                return "<span class=\"{$colourMap[$matches[1]]}\">{$matches[2]}</span>";
            },
            $data
        );

        return $data;
    }

    /**
     * Convert track code (000) into full name
     *
     * @param string $trackCode
     *
     * @return mixed string|null
     */
    protected function parseTrackCode($trackCode)
    {
        if (array_key_exists($trackCode, self::trackCodes)) {
            return self::trackCodes[$trackCode];
        }

        return null;
    }

    /**
     * Parse car bits into car codes and fullnames
     *
     * @param int $cars
     *
     * @return array
     */
    protected function parseCarBits($cars)
    {
        $carsList = [];

        foreach (self::carBits as $bit => $car) {
            if ($cars === ($cars | $bit)) {
                $carsList[$car]['code'] = $car;
                $carsList[$car]['full_name'] = self::carCodes[$car];
            }
        }

        return $carsList;
    }

    /**
     * Parse rule bits into rule names
     *
     * @param int $rules
     *
     * @return array
     */
    protected function parseRuleBits($rules)
    {
        $rulesList = [];

        foreach (self::ruleBits as $bit => $rule) {
            if ($rules === ($rules | $bit)) {
                $rulesList[] = $rule;
            }
        }

        return $rulesList;
    }

    /**
     * Remove any ingame colour codes (^1)
     *
     * @param string $data
     *
     * @return string
     */
    protected function stripColourCodes($data)
    {
        if (false === strpos($data, '^')) {
            return $data;
        }

        return preg_replace('@\^[0-8]@', '', $data);
    }

    /**
     * Determine race distance in laps or hours
     *
     * @param int $laps
     *
     * @return array
     */
    protected function getRaceDurations($laps)
    {
        $durations = [
            'laps'          => $laps,
            'hours'         => 0,
        ];

        if ($laps > 100) {
            if ($laps < 191) {
                $durations['laps'] = ((($laps - 100) * 10) + 100);
            } else {
                $durations['laps'] = 0;
                $durations['hours'] = $laps - 190;
            }
        }

        return $durations;
    }
}
