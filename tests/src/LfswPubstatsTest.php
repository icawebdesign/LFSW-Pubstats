<?php
/**
 * Description here...
 *
 * @author
 */
namespace Src;

use Icawebdesign\LfswPubstats\LfswPubstats;

class LfswPubstatsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function my_test_method_returns_true()
    {
        $lfswPubstats = new LfswPubstats();
        $this->assertTrue($lfswPubstats->testMethod());
    }
}
