<?php
namespace Src;

use Icawebdesign\LfswPubstats\LfswPubstats;

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
}
