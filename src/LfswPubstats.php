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
        '000'           => [
            'code'          => 'BL1',
            'full_name'     => 'Blackwood GP',
            'distance'      => 2.054,
        ],
        '001' 			=> [
            'code'          => 'BL1R',
            'full_name'     => 'Blackwood GP reversed',
            'distance'      => 2.054,
        ],
        '002' 			=> [
            'code'          => 'BL1X',
            'full_name'     => 'Blackwood GP X',
            'distance'      => 0,
        ],
        '003' 			=> [
            'code'          => 'BL1Y',
            'full_name'     => 'Blackwood GP Y',
            'distance'      => 0,
        ],
        '010' 			=> [
            'code'          => 'BL2',
            'full_name'     => 'Blackwood RallyX',
            'distance'      => 1.142,
        ],
        '011' 			=> [
            'code'          => 'BL2R',
            'full_name'     => 'Blackwood RallyX reversed',
            'distance'      => 1.142,
        ],
        '020' 			=> [
            'code'          => 'BL3',
            'full_name'     => 'Blackwood Car Park',
            'distance'      => 0,
        ],
        '100' 			=> [
            'code'          => 'SO1',
            'full_name'     => 'South City Classic',
            'distance'      => 1.263,
        ],
        '101' 			=> [
            'code'          => 'SO1R',
            'full_name'     => 'South City Classic reversed',
            'distance'      => 1.263,
        ],
        '110' 			=> [
            'code'          => 'SO2',
            'full_name'     => 'South City Sprint Track 1',
            'distance'      => 1.272,
        ],
        '111' 			=> [
            'code'          => 'SO2R',
            'full_name'     => 'South City Sprint Track 1 reversed',
            'distance'      => 1.272,
        ],
        '120' 			=> [
            'code'          => 'SO3',
            'full_name'     => 'South City Sprint Track 2',
            'distance'      => 0.828,
        ],
        '121' 			=> [
            'code'          => 'SO3R',
            'full_name'     => 'South City Sprint Track 2 reversed',
            'distance'      => 0.828,
        ],
        '130' 			=> [
            'code'          => 'SO4',
            'full_name'     => 'South City Long',
            'distance'      => 2.503,
        ],
        '131' 			=> [
            'code'          => 'SO4R',
            'full_name'     => 'South City Long reversed',
            'distance'      => 2.503,
        ],
        '140' 			=> [
            'code'          => 'SO5',
            'full_name'     => 'South City Town Course',
            'distance'      => 1.954,
        ],
        '141' 			=> [
            'code'          => 'SO5R',
            'full_name'     => 'South City Town Course reversed',
            'distance'      => 1.954,
        ],
        '150'           => [
            'code'          => 'SO6',
            'full_name'     => 'South City Chicane Course',
            'distance'      => 1.812,
        ],
        '151'           => [
            'code'          => 'SO6R',
            'full_name'     => 'South City Chicane Course reversed',
            'distance'      => 1.812,
        ],
        '200' 			=> [
            'code'          => 'FE1',
            'full_name'     => 'Fern Bay Club',
            'distance'      => 0.984,
        ],
        '201' 			=> [
            'code'          => 'FE1R',
            'full_name'     => 'Fern Bay Club reversed',
            'distance'      => 0.984,
        ],
        '210' 			=> [
            'code'          => 'FE2',
            'full_name'     => 'Fern Bay Green',
            'distance'      => 1.917,
        ],
        '211' 			=> [
            'code'          => 'FE2R',
            'full_name'     => 'Fern Bay Green reversed',
            'distance'      => 1.917,
        ],
        '220' 			=> [
            'code'          => 'FE3',
            'full_name'     => 'Fern Bay Gold',
            'distance'      => 2.183,
        ],
        '221' 			=> [
            'code'          => 'FE3R',
            'full_name'     => 'Fern Bay Gold reversed',
            'distance'      => 2.183,
        ],
        '230' 			=> [
            'code'          => 'FE4',
            'full_name'     => 'Fern Bay Black',
            'distance'      => 4.075,
        ],
        '231' 			=> [
            'code'          => 'FE4R',
            'full_name'     => 'Fern Bay Black reversed',
            'distance'      => 4.075,
        ],
        '240' 			=> [
            'code'          => 'FE5',
            'full_name'     => 'Fern Bay Rallycross',
            'distance'      => 1.253,
        ],
        '241' 			=> [
            'code'          => 'FE5R',
            'full_name'     => 'Fern Bay Rallycross reversed',
            'distance'      => 1.253,
        ],
        '250' 			=> [
            'code'          => 'FE6',
            'full_name'     => 'Fern Bay Rally X Green',
            'distance'      => 0.462,
        ],
        '251' 			=> [
            'code'          => 'FE6R',
            'full_name'     => 'Fern Bay Rally X Green reversed',
            'distance'      => 0.462,
        ],
        '300' 			=> [
            'code'          => 'AU1',
            'full_name'     => 'Autocross',
            'distance'      => 0,
        ],
        '310' 			=> [
            'code'          => 'AU2',
            'full_name'     => 'Autocross Skid pad',
            'distance'      => 0,
        ],
        '320' 			=> [
            'code'          => 'AU3',
            'full_name'     => 'Autocross Drag strip',
            'distance'      => 1.376,
        ],
        '330' 			=> [
            'code'          => 'AU4',
            'full_name'     => 'Autocross 8 lane drag strip',
            'distance'      => 1.376,
        ],
        '400' 			=> [
            'code'          => 'KY1',
            'full_name'     => 'Kyoto Ring Oval',
            'distance'      => 1.851,
        ],
        '401' 			=> [
            'code'          => 'KY1R',
            'full_name'     => 'Kyoto Ring Oval reversed',
            'distance'      => 1.851,
        ],
        '410' 			=> [
            'code'          => 'KY2',
            'full_name'     => 'Kyoto Ring National',
            'distance'      => 3.192,
        ],
        '411' 			=> [
            'code'          => 'KY2R',
            'full_name'     => 'Kyoto Ring National reversed',
            'distance'      => 3.192,
        ],
        '420' 			=> [
            'code'          => 'KY3',
            'full_name'     => 'Kyoto Ring GP Long',
            'distance'      => 4.583,
        ],
        '421' 			=> [
            'code'          => 'KY3R',
            'full_name'     => 'Kyoto Ring GP Long reversed',
            'distance'      => 4.583,
        ],
        '500' 			=> [
            'code'          => 'WE1',
            'full_name'     => 'Westhill National',
            'distance'      => 2.73,
        ],
        '501' 			=> [
            'code'          => 'WE1R',
            'full_name'     => 'Westhill National reversed',
            'distance'      => 2.73,
        ],
        '510'           => [
            'code'          => 'WE2',
            'full_name'     => 'Westhill International',
            'distance'      => 3.57,
        ],
        '511'           => [
            'code'          => 'WE2R',
            'full_name'     => 'Westhill International reversed',
            'distance'      => 3.57,
        ],
        '520'           => [
            'code'          => 'WE3',
            'full_name'     => 'Westhill Car Park',
            'distance'      => 0,
        ],
        '530'           => [
            'code'          => 'WE4',
            'full_name'     => 'Westhill Karting',
            'distance'      => 0.3,
        ],
        '531'           => [
            'code'          => 'WE4R',
            'full_name'     => 'Westhill Karting reversed',
            'distance'      => 0.3,
        ],
        '540'           => [
            'code'          => 'WE5',
            'full_name'     => 'Westhill Karting International',
            'distance'      => 0.82,
        ],
        '541'           => [
            'code'          => 'WE5R',
            'full_name'     => 'Westhill Karting International reversed',
            'distance'      => 0.82,
        ],
        '600' 			=> [
            'code'          => 'AS1',
            'full_name'     => 'Aston Cadet',
            'distance'      => 1.161,
        ],
        '601' 			=> [
            'code'          => 'AS1R',
            'full_name'     => 'Aston Cadet reversed',
            'distance'      => 1.161,
        ],
        '610' 			=> [
            'code'          => 'AS2',
            'full_name'     => 'Aston Club',
            'distance'      => 1.911,
        ],
        '611' 			=> [
            'code'          => 'AS2R',
            'full_name'     => 'Aston Club reversed',
            'distance'      => 1.911,
        ],
        '620' 			=> [
            'code'          => 'AS3',
            'full_name'     => 'Aston National',
            'distance'      => 3.480,
        ],
        '621' 			=> [
            'code'          => 'AS3R',
            'full_name'     => 'Aston National reversed',
            'distance'      => 3.480,
        ],
        '630' 			=> [
            'code'          => 'AS4',
            'full_name'     => 'Aston Historic',
            'distance'      => 5.026,
        ],
        '631' 			=> [
            'code'          => 'AS4R',
            'full_name'     => 'Aston Historic reversed',
            'distance'      => 5.026,
        ],
        '640' 			=> [
            'code'          => 'AS5',
            'full_name'     => 'Aston Grand prix',
            'distance'      => 5.469,
        ],
        '641' 			=> [
            'code'          => 'AS5R',
            'full_name'     => 'Aston Grand prix reversed',
            'distance'      => 5.469,
        ],
        '650'           => [
            'code'          => 'AS6',
            'full_name'     => 'Aston Grand Touring',
            'distance'      => 4.972,
        ],
        '651'           => [
            'code'          => 'AS6R',
            'full_name'     => 'Aston Grand Touring reversed',
            'distance'      => 4.972,
        ],
        '660'           => [
            'code'          => 'AS7',
            'full_name'     => 'Aston North',
            'distance'      => 3.211,
        ],
        '661'           => [
            'code'          => 'AS7R',
            'full_name'     => 'Aston North reversed',
            'distance'      => 3.211,
        ],
        '700'           => [
            'code'          => 'RO1',
            'full_name'     => 'ISSC',
            'distance'      => 1.930,
        ],
        '701'           => [
            'code'          => 'RO1R',
            'full_name'     => 'ISSC reversed',
            'distance'      => 1.930,
        ],
        '710'           => [
            'code'          => 'RO2',
            'full_name'     => 'National',
            'distance'      => 1.680,
        ],
        '711'           => [
            'code'          => 'RO2R',
            'full_name'     => 'National reversed',
            'distance'      => 1.680,
        ],
        '720'           => [
            'code'          => 'RO3',
            'full_name'     => 'Oval',
            'distance'      => 1.490,
        ],
        '721'           => [
            'code'          => 'RO3R',
            'full_name'     => 'Oval reversed',
            'distance'      => 1.490,
        ],
        '730'           => [
            'code'          => 'RO4',
            'full_name'     => 'ISSC Long',
            'distance'      => 2.050,
        ],
        '731'           => [
            'code'          => 'RO4R',
            'full_name'     => 'ISSC Long reversed',
            'distance'      => 2.050,
        ],
        '740'           => [
            'code'          => 'RO5',
            'full_name'     => 'Lake',
            'distance'      => 0.620,
        ],
        '741'           => [
            'code'          => 'RO5R',
            'full_name'     => 'Lake reversed',
            'distance'      => 0.620,
        ],
        '750'           => [
            'code'          => 'RO6',
            'full_name'     => 'Handling',
            'distance'      => 0.990,
        ],
        '751'           => [
            'code'          => 'RO6R',
            'full_name'     => 'Handling reversed',
            'distance'      => 0.990,
        ],
        '760'           => [
            'code'          => 'RO7',
            'full_name'     => 'International',
            'distance'      => 2.420,
        ],
        '761'           => [
            'code'          => 'RO7R',
            'full_name'     => 'International reversed',
            'distance'      => 2.420,
        ],
        '770'           => [
            'code'          => 'RO8',
            'full_name'     => 'Historic',
            'distance'      => 2.240,
        ],
        '771'           => [
            'code'          => 'RO8R',
            'full_name'     => 'Historic reversed',
            'distance'      => 2.240,
        ],
        '780'           => [
            'code'          => 'RO9',
            'full_name'     => 'Historic Short',
            'distance'      => 1.370,
        ],
        '781'           => [
            'code'          => 'RO9R',
            'full_name'     => 'Historic Short reversed',
            'distance'      => 1.370,
        ],
        '790'           => [
            'code'          => 'RO10',
            'full_name'     => 'International Long',
            'distance'      => 2.550,
        ],
        '791'           => [
            'code'          => 'RO10R',
            'full_name'     => 'International Long reversed',
            'distance'      => 2.550,
        ],
        '7100'          => [
            'code'          => 'RO11',
            'full_name'     => 'Sports Car',
            'distance'      => 1.680,
        ],
        '7101'          => [
            'code'          => 'RO11R',
            'full_name'     => 'Sports Car reversed',
            'distance'      => 1.680,
        ],
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

    const hotlapFlagBits = [
        1               => 'LEFTHANDDRIVE',
        8               => 'AUTOGEAR',
        16              => 'SHIFTER',
        64              => 'BRAKEHELP',
        128             => 'AXISCLUTCH',
        512             => 'AUTOCLUTCH',
        1024            => 'MOUSESTEER',
        2048            => 'KN',
        4096            => 'KS',
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
    protected function parseTrackNumber($trackCode)
    {
        if (array_key_exists($trackCode, self::trackCodes)) {
            $track = self::trackCodes[$trackCode];
            $track['number'] = $trackCode;

            return $track;
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

    /**
     * Convert miles to kms
     *
     * @param double $miles
     * @param int $precision
     *
     * @return string
     */
    protected function milesToKms($miles, $precision = 3)
    {
        $km = 1.609344;

        return number_format($miles * $km, $precision, '.', '');
    }

    /**
     * Convert kms to miles
     *
     * @param double $kms
     * @param int $precision
     *
     * @return string
     */
    protected function kmsToMiles($kms, $precision = 3)
    {
        $miles = 0.6214;

        return number_format($kms * $miles, $precision, '.', '');
    }

    /**
     * Convert online status code into string
     *
     * @param $statusCode
     *
     * @return string
     */
    protected function getOnlineStatus($statusCode)
    {
        $status = 'Unknown';

        switch ($statusCode) {
            case 0:
                $status = 'Offline';
                break;

            case 1:
                $status = 'Spectating';
                break;

            case 2:
                $status = 'In pits';
                break;

            case 3:
                $status = 'In race';
                break;
        }

        return $status;
    }

    /**
     * Get data from LFSWorld
     *
     * @return string
     */
    protected function getLfswData()
    {
        $data = '';

        try {
            $fp = fopen($this->lfswUrl, 'rb');
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            die();
        }

        if (is_resource($fp)) {
            while (!feof($fp)) {
                $data .= fread($fp, 1024);
            }

            fclose($fp);
        }

        return $data;
    }

    /**
     * Get hotlap flags from bits
     *
     * @param $flags
     *
     * @return array
     */
    protected function parseHotlapFlagBits($flags)
    {
        $flagsList = [];

        foreach (self::hotlapFlagBits as $bit => $flag) {
            if ($flags === ($flags | $bit)) {
                $flagsList[] = $flag;
            }
        }

        // If MOUSESTEER, KN or KS not found, assume WHEEL for controller
        if (($flags !== ($flags | 1024)) && ($flags !== ($flags | 2048)) && ($flags !== ($flags | 4096))) {
            $flagsList[] = 'WHEEL';
        }

        return $flagsList;
    }

    /**
     * Generate download URL for a specific hotlap
     *
     * @param $hotlapId
     *
     * @return string
     */
    protected function getHotlapDownloadUrl($hotlapId)
    {
        return "{$this->getConfig('LFSW_HL_DL_URL')}?file={$hotlapId}";
    }

    /**
     * Convert milliseconds into 00:00:00.000 time
     *
     * @param $milliseconds
     *
     * @return mixed
     */
    protected function millisecondsToTime($milliseconds)
    {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);

        $milliseconds = $milliseconds % 1000;
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;

        $time = sprintf('%u:%02u:%02u.%03u', $hours, $minutes, $seconds, $milliseconds);

        return $time;
    }
}
