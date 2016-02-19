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
}
