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



namespace Src;

use Icawebdesign\LfswPubstats\LfswPubstats;
use Icawebdesign\LfswPubstats\LfswPubstatsHosts;
use Icawebdesign\LfswPubstats\LfswPubstatsRacer;

class LfswPubstatsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function log_should_write_to_log_file()
    {
        $lfswPubStats = new LfswPubstats();
        $lfswPubStats->log('This is a test message', 'info', ['foo' => 'bar']);

        $this->assertFileExists(__DIR__ . '/../../log/pubstats.log');
    }

    /** @test */
    public function read_config_file_should_store_data_in_array()
    {
        $lfswPubstats = new LfswPubstats();
        $this->assertArrayHasKey('IDKEY', $lfswPubstats->getConfig());
    }

    /** @test */
    public function config_value_has_idkey_from_dotenv()
    {
        $lfswPubstats = new LfswPubstats('.env.example');
        $this->assertTrue('THIS SHOULD BE YOUR LFSWORLD PUBSTATS IDKEY' === $lfswPubstats->getConfig()['IDKEY']);
    }

    /** @test */
    public function config_should_return_a_single_value_when_key_is_specified()
    {
        $lfswPubstats = new LfswPubstats('.env.example');
        $this->assertEquals('THIS SHOULD BE YOUR LFSWORLD PUBSTATS IDKEY', $lfswPubstats->getConfig('IDKEY'));
    }

    /** @test */
    public function get_hosts_online_should_return_a_collection_of_host_data()
    {
        $lfswPubstatsHosts = new LfswPubstatsHosts();
        $hostData = $lfswPubstatsHosts->getHostsOnline();

        $this->assertInternalType('array', $hostData);
    }

    /** @test */
    public function get_racer_stats_should_return_stdclass_object()
    {
        $lfswPubstatsRacer = new LfswPubstatsRacer();
        $racerData = $lfswPubstatsRacer->getRacerStats('Ian.H');

        $this->assertInstanceOf('stdClass', $racerData);
    }

    /** @test */
    public function get_hotlaps_for_specified_racer()
    {
        $lfswPubstatsRacer = new LfswPubstatsRacer();
        $data = $lfswPubstatsRacer->getRacerHotlaps('flotch');

        $this->assertNotEmpty($data);
    }
}
