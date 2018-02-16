<?php declare (strict_types = 1);
use GenomeSequence\Shotgun;
use PHPUnit\Framework\TestCase;

/**
 * A unit testing class for all GenomeSequencing methods.
 */
class ShotgunTest extends TestCase {

	/**
	 * Path to the fragmented text document.
	 *
	 * @var string
	 */
	private $filePath;

	/**
	 * Object GenomeSequence that is being tested.
	 *
	 * @var GenomeSequence
	 */
	private $Genome;

	/**
	 * Sets up the 'environment' for each test.
	 *
	 * @return void
	 */
	public function setUp() {
		$this->filePath = dirname(__FILE__) . "/fixtures/source.txt";
		$this->Genome = new Shotgun($this->filePath);
	}

	/**
	 * Verify that the class can be instantiated.
	 *
	 * @return void
	 */
	public function testInstatiation() {
		$this->assertTrue(is_object($this->Genome));
	}

	/**
	 * Verify that the get and set methods work as expected.
	 *
	 * @return void
	 */
	public function testRead() {
		$this->assertTrue(is_object($this->Genome->setFragments()));
		$this->assertTrue(is_array($this->Genome->getFragments()));
	}

	/**
	 * Verify that the getFirstCharacter() retrives the first character.
	 *
	 * @return void
	 */
	public function testGetFirstCharacter() {
		$this->Genome->setFragments();
		$character = $this->Genome->getFirstCharacter("this is just a sample string");
		$this->assertEquals("t", $character);
	}

	/**
	 * Verify that inString() accurately reads if a character is in a string.
	 *
	 * @return void
	 */
	public function testInString() {
		$this->Genome->setFragments();
		$true = $this->Genome->inString('i');
		$false = $this->Genome->inString('x');
		$this->assertTrue($true);
		$this->assertFalse($false);
	}

	/**
	 * Verify that the position of a matched character is appropriately found.
	 *
	 * @return void
	 */
	public function testGetPositionOfMatch() {
		$this->Genome->setFragments();
		$position = $this->Genome->getPositionOfMatch('i', 3);
		$this->assertSame($position, 0);
	}

	/**
	 * Verify that getNumberOfMatches() accurately returns an integer value.
	 *
	 * @return void
	 */
	public function testGetNumberOfMatches() {
		$this->Genome->setFragments();
		$result = $this->Genome->getNumberOfMatches(1, 3);
		$this->assertTrue(is_int($result));
		$this->assertSame(3, 3);
	}

	/**
	 * Verify that the class property is merged and removed where necessary.
	 *
	 * @return void
	 */
	public function testMergeFragments() {
		$this->Genome->setFragments();
		$this->Genome->mergeFragments(2, 1, 5);
		$this->assertSame(3, count($this->Genome->getFragments()));
	}

	/**
	 * Verify that the string with the most similarities is correctly being sent back
	 * to be the first to be merged into it's pair.
	 *
	 * @return void
	 */
	public function testFindSimilarities() {
		$this->Genome->setFragments();
		$similarities = $this->Genome->findSimilarities();
		$this->assertEquals(2, $similarities["maxIndex"]);
	}

	/**
	 * Verify if a match is significant, even if there is only one match.
	 *
	 * @return void
	 */
	public function testIsMatchSignificant() {
		$this->filePath = dirname(__FILE__) . "/fixtures/significant.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments();
		$true = $this->Genome->isMatchSignificant(0, 1);
		$this->assertTrue($true);

		$this->filePath = dirname(__FILE__) . "/fixtures/not-significant.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments();
		$false = $this->Genome->isMatchSignificant(0, 1);
		$this->assertFalse($false);
	}

	/**
	 * Verify that the fragmented text string are constructed into their original sentence.
	 *
	 * @return void
	 */
	public function testRecompileFragments() {
		$this->Genome->setFragments()
			->recompileFragments();
		$this->assertSame(
			$this->Genome->getFragments()[0],
			"alliswellthatendswell"
		);
	}

	/**
	 * Verify that multiple test strings work in the same manner as the defined string for the challenge.
	 *
	 * @see Bill & Ted's Excellent Adventure
	 * @see Thor: Ragnarok
	 * @return void
	 */
	public function testRecompileMovieQuotes() {
		$this->filePath = dirname(__FILE__) . "/fixtures/excellent.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments()
			->recompileFragments();
		$this->assertSame(
			$this->Genome->getFragments()[0],
			"billstrangethingsareafootatthecirclek"
		);

		$this->filePath = dirname(__FILE__) . "/fixtures/ragnarok.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments()
			->recompileFragments();
		$this->assertSame(
			$this->Genome->getFragments()[0],
			"We’rethesame,youandI,justacoupleofhotheadedfools.Yes,same.Hulklikefire,Thorlikewater.Well,we’rekindofbothlikefire.ButHulklikeragingfire.Thorlikesmolderingfire."
		);
	}

	/**
	 * Verifying that is there is no match between the characters the fragment is concatenated together
	 * in any order.
	 *
	 * NOTE: When reading the expected value you must read it in Yoda's voice.
	 *
	 * @return void
	 */
	public function testNoMatch() {
		$this->filePath = dirname(__FILE__) . "/fixtures/no-match.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments()
			->recompileFragments();
		$this->assertSame(
			$this->Genome->getFragments()[0],
			"matchatallthiswillnot"
		);
	}

	/**
	 * Verify that if the source file is randomized it's fragments will still compile correctly.
	 *
	 * @return void
	 */
	public function testRandomOrderMatch() {
		$this->filePath = dirname(__FILE__) . "/fixtures/random-order-source.txt";
		$this->Genome = new Shotgun($this->filePath);
		$this->Genome->setFragments()
			->recompileFragments();
		$this->assertSame(
			$this->Genome->getFragments()[0],
			"alliswellthatendswell"
		);
	}
}