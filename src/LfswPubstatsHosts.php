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

/**
 * Class LfswPubstatsHosts
 *
 * @package Icawebdesign\LfswPubstats
 */
class LfswPubstatsHosts extends LfswPubstats
{
    /**
     * LfswPubstatsHosts constructor.
     */
    public function __construct()
    {
        return parent::__construct();
    }

    public function getHostsOnline()
    {
        $this->setLfswUrl(['action' => 'hosts']);
        $hostData = '';
        $offset = 0;
        $hostList = [];

        try {
            $fp = fopen($this->lfswUrl, 'rb');
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            die();
        }

        if (is_resource($fp)) {
            while (!feof($fp)) {
                $hostData .= fread($fp, 1024);
            }

            $hostList = json_decode($hostData);

            fclose($fp);

            foreach ($hostList as &$host) {
                $host->hostnameColour = $this->parseColourCodes($host->hostname);
                $host->hostnamePlain = $this->stripColourCodes($host->hostname);
                $host->tcrm = unpack('c1track/c1config/c1track_rev/c1max_players', $host->tcrm);
                $host->trackCode = "{$host->tcrm['track']}{$host->tcrm['config']}{$host->tcrm['track_rev']}";
                $host->trackName = $this->parseTrackCode($host->trackCode);
                $host->tmlt = unpack('c1server_type/c1version/c1patch/c1test_version', $host->tmlt);
                $host->tmlt['version'] /= 10;
                $host->tmlt['patch'] = chr($host->tmlt['patch']);
                $host->carsList = $this->parseCarBits($host->cars);
                $host->rulesList = $this->parseRuleBits($host->rules);

                $host->raceDuration = $this->getRaceDurations($host->laps);
                $host->practice = (
                    (0 ===$host->raceDuration['laps']) &&
                    (0 === $host->raceDuration['hours']
                ) ? true : false);
            }

            /*if ('' !== trim($hostData)) {
                while (true) {
                    $hostInfo = substr($hostData, $offset, 53);

                    if (53 !== strlen($hostInfo)) {
                        break;
                    }

                    $data = unpack(
                        'a32hostname/c1server_type/c1version/c1patch/c1test_version/c1track/c1config/c1track_rev/c1max_players/L1cars/L1rules/c1laps/c1qual_mins/x2/c1num_players',
                        $hostInfo
                    );
                    $offset += 53;

                    $racers = explode('&', chunk_split(substr($hostData, $offset, ($data['num_players'] * 24)), 24, '&'));
                    array_pop($racers);
                    $data['racers'] = array_map('rtrim', $racers);
                    $offset += ($data['num_players'] * 24);

                    $data['hours'] = 0;

                    if ($data['laps'] > 100) {
                        if ($data['laps'] < 191) {
                            $data['laps'] = ((($data['laps'] - 100) * 10) + 100);
                        } else {
                            $data['laps'] = 0;
                            $data['hours'] = $data['laps'] - 191;
                        }
                    }

                    $data['track'] = "{$data['track']}{$data['config']}{$data['track_rev']}";
                    $data['racers_json'] = json_encode($data['racers']);
                    $data['version'] /= 10;
                    $data['patch'] = chr($data['patch']);
                    // @todo
                    //$data['hostname_colour'] = $this->parseColourCodes($data['hostname']);
                    // @todo
                    //$data['server_type'] = $this->getServerTypes[$data['server_type']];
                    // @todo
                    //$data['cars'] = $this->parseCarBits($data['cars']);
                    // @todo
                    //$data['track'] = $this->parseTrackBits($data['track']);
                    // @todo
                    //$data['rules'] = $this->parseRuleBits($data['rules']);
                    $data['access'] = 'Public';

                    $hostList[] = (object)$data;
                }

                return $hostList;
            }*/

            return $hostList;
        }

        return null;
    }
}
