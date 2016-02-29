<?php

namespace Icawebdesign\LfswPubstats;

class LfswPubstatsRacer extends LfswPubstats
{
    /**
     * LfswPubstatsRacer constructor.
     */
    public function __construct()
    {
        return parent::__construct();
    }

    /**
     * Get statistics for specific racer
     * 
     * @param $racerName
     *
     * @return array|null
     */
    public function getRacerStats($racerName)
    {
        $this->setLfswUrl(['action' => 'pst', 'racer' => $racerName]);
        $racerData = $this->getLfswData();

        if ('pst: no valid username' === $racerData) {
            throw new \Exception('No valid username');
        }

        $racerStats = json_decode($racerData)[0];
        $racerStats->hostnamePlain = $this->stripColourCodes($racerStats->hostname);

        // Convert distance to miles
        $racerStats->distance = $this->kmsToMiles($racerStats->distance / 1000);

        // Online status
        $racerStats->status = $this->getOnlineStatus($racerStats->ostatus);

        // Convert fuel from CL into litres
        $racerStats->fuel = (double)$racerStats->fuel / 100;

        // Get track info from track code
        $racerStats->track = $this->parseTrackNumber($racerStats->track);

        return $racerStats;
    }

    /**
     * @param $racerName
     *
     * @return bool|null|string
     */
    public function getRacerHotlaps($racerName)
    {
        $this->setLfswUrl(['action' => 'hl', 'racer' => $racerName]);
        $data = $this->getLfswData();

        if ((null === $data) || ('' === trim($data))) {
            return null;
        }

        // Check error
        if ('hl: no hotlaps found' === $data) {
            throw new \Exception('No hotlaps found for racer');
        }

        $hotlaps = json_decode($data);

        // Get track info for hotlaps
        foreach ($hotlaps as &$hotlap) {
            $hotlap->track = $this->parseTrackNumber($hotlap->track);
            $hotlap->flags = $this->parseHotlapFlagBits($hotlap->flags_hlaps);
            $hotlap->downloadLink = $this->getHotlapDownloadUrl($hotlap->id_hl);
            $hotlap->laptime = $this->millisecondsToTime($hotlap->laptime);
            $hotlap->split1 = $this->millisecondsToTime($hotlap->split1);
            $hotlap->split2 = $this->millisecondsToTime($hotlap->split2);
            $hotlap->split3 = $this->millisecondsToTime($hotlap->split3);
        }

        return $hotlaps;
    }
}
