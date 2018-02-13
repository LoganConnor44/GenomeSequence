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

	// public function testIntersection() {
	// 	$this->Genome->setFragments();

	// 	foreach ($this->Genome->getFragments() as $fragment) {
	// 		var_dump($this->Genome->getBeginningAndEnd($fragment));
	// 		echo PHP_EOL;
	// 	}
	// }

	public function testRecompileFrags() {
		$this->Genome->setFragments()
			->recompileFragments();
	}

}
