<?php

namespace Icawebdesign\LfswPubstats;

class LfswPubstatsRacer extends LfswPubstats
{
    public function __construct()
    {
        return parent::__construct();
    }

    public function getRacerStats($racerName)
    {
        $this->setLfswUrl(['action' => 'pst', 'racer' => $racerName]);

        $racerData = '';

        try {
            $fp = fopen($this->lfswUrl, 'rb');
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
            die();
        }

        if (is_resource($fp)) {
            while (!feof($fp)) {
                $racerData .= fread($fp, 1024);
            }

            if ('pst: no valid username' === $racerData) {
                return null;
            }

            fclose($fp);

            $racerStats = json_decode($racerData)[0];
            $racerStats->hostnamePlain = $this->stripColourCodes($racerStats->hostname);

            // Convert distance to miles
            //$racerStats->distance = $this

            return $racerStats;
        }

        return null;
    }
}
