<?php
use AderantChallenge\GenomeSequence;
use PHPUnit\Framework\TestCase;

/**
 * A unit testing class for all SocialMedia children objects.
 */
class AderantChallengeTest extends TestCase {

	private $filePath;

	private $Genome;

	public function setUp() {
		$this->filePath = dirname(__FILE__) . "/stubs/source.txt";
		$this->Genome = new GenomeSequence($this->filePath);
	}

	public function testInstatiation() {
		$this->assertTrue(is_object($this->Genome));
	}

	public function testRead() {
		$this->assertTrue(is_object($this->Genome->setFragments()));
		$this->assertTrue(is_array($this->Genome->getFragments()));
	}

	public function testGetBeginning() {
		$this->Genome->setFragments();
		$character = $this->Genome->getBeginning("this is just a sample string");
		$this->assertEquals("t", $character);
	}

	public function testInString() {
		$this->Genome->setFragments();
		$true = $this->Genome->inString('i');
		$false = $this->Genome->inString('x');
		$this->assertTrue($true);
		$this->assertFalse($false);		
	}

	public function testGetPositionOfChar() {
		$this->Genome->setFragments();
		$position = $this->Genome->getPositionOfMatch('i', 3);
		$this->assertSame($position, 0);
	}

	public function testGetNumberOfMatches() {
		$this->Genome->setFragments();
		$result = $this->Genome->getNumberOfMatches('e', 1, 3);
		$this->assertTrue(is_array($result));
		$this->assertSame(3, key($result));
		$this->assertSame(3, $result[3]);
	}

}





























