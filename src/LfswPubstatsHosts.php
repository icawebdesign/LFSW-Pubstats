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

    /**
     * Get collection of online hosts
     *
     * @return array
     */
    public function getHostsOnline()
    {
        $this->setLfswUrl(['action' => 'hosts']);
        $hostData = $this->getLfswData();

        $hostList = json_decode($hostData);

        foreach ($hostList as &$host) {
            $host->hostnameColour = $this->parseColourCodes($host->hostname);
            $host->hostnamePlain = $this->stripColourCodes($host->hostname);

            $host->tcrm = unpack('c1track/c1config/c1track_rev/c1max_players', $host->tcrm);
            $trackNumber = "{$host->tcrm['track']}{$host->tcrm['config']}{$host->tcrm['track_rev']}";
            $host->track = $this->parseTrackNumber($trackNumber);

            $host->tmlt = unpack('c1server_type/c1version/c1patch/c1test_version', $host->tmlt);
            $host->tmlt['version'] /= 10;
            $host->tmlt['patch'] = chr($host->tmlt['patch']);
            $host->carsList = $this->parseCarBits($host->cars);
            $host->rulesList = $this->parseRuleBits($host->rules);

            $host->raceDuration = $this->getRaceDurations($host->laps);
            $host->practice = (
                (0 === $host->raceDuration['laps']) &&
                (0 === $host->raceDuration['hours']
            ) ? true : false);
        }

        return $hostList;
    }
}
