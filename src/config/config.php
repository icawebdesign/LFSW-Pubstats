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
use Icawebdesign\LfswPubstats\LfswPubstats;

return [
    'IDKEY'             => LfswPubstats::env('IDKEY', 'LFSWORLD PUBSTATS IDKEY'),
    'API_VERSION'       => LfswPubstats::env('API_VERSION', null),      // 1.1, 1.2, 1.3, 1.4, 1.5, null for latest version
    'LFSW_URL'          => 'http://www.lfsworld.net/pubstat/get_stat2.php',
    'LFSW_HL_DL_URL'    => 'http://www.lfsworld.net/get_spr.php',
];