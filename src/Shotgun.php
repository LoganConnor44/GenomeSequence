<?php declare (strict_types = 1);
namespace GenomeSequence;

/**
 * A class to reconstruct fragmented text strings back into an original document.
 */
class Shotgun {

	/**
	 * Path to the fragmented text document.
	 *
	 * @var string
	 */
	private $filePath;

	/**
	 * Fragmented text strings.
	 *
	 * @var array
	 */
	private $fragments;

	/**
	 * Instantiated with a path to the source text file.
	 *
	 * @param string $filePath Path to the fragmented text document.
	 */
	public function __construct(string $filePath) {
		$this->filePath = $filePath;
	}

	/**
	 * Sets the class property by reading a text file.
	 *
	 * @return Shotgun
	 */
	public function setFragments() : Shotgun {
		$handle = fopen($this->filePath, "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$this->fragments[] = str_replace(" ", "", trim($buffer));
			}
			fclose($handle);
		}
		return $this;
	}

	/**
	 * Returns the private property of $fragments.
	 *
	 * @return array
	 */
	public function getFragments() : array {
		return $this->fragments;
	}

	/**
	 * Returns the first character in a given string.
	 *
	 * @param string $fragment The given string.
	 * @return string
	 */
	public function getFirstCharacter(string $fragment) : string {
		return substr($fragment, 0, 1);
	}

	/**
	 * Returns a boolean depending on if a given character is within the class
	 * property $fragments.
	 *
	 * @param string $begChar The given character.
	 * @return boolean
	 */
	public function inString(string $begChar) : bool {
		foreach ($this->fragments as $fragment) {
			$result = strpos($fragment, $begChar);

			if ($result) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Returns the indexed value of the tested stringed-array.
	 *
	 * @param string  $character  The given character to search by.
	 * @param integer $excludeKey Exclude the current array-value.
	 * @return integer
	 */
	public function getPositionOfMatch(string $character, int $excludeKey) : int {
		$fragments = $this->fragments;
		unset($fragments[$excludeKey]);

		foreach ($fragments as $index => $fragment) {
			$result = strpos($fragment, $character);

			if ($result) {
				return $index;
			}
		}
	}

	/**
	 * Returns the amount of matches found when comparing two strings.
	 *
	 * @param integer $source     The index of the original fragment.
	 * @param integer $matchIndex The index of the fragment being compared.
	 * @return integer
	 */
	public function getNumberOfMatches(int $source, int $matchIndex) : int {
		$start = 0;
		$length = 1;
		$characters = "";
		$significant = TRUE;

		while (TRUE) {
			$builder = substr($this->fragments[$source], $start, $length);
			$characters .= $builder;
			$charsExist = preg_match("[$characters]", $this->fragments[$matchIndex]);
			$matches = strlen($characters);
			
			if (!$charsExist || $matches === strlen($this->fragments[$source])) {
				return $matches - 1;
			}
			$start++;
		}
	}

	/**
	 * Iterates through each stringed-array and does a comparision of the most similar text files.
	 * Identifies the most appropriate strings to merge and sets these as 'max' values.
	 *
	 * @return array
	 */
	public function findSimilarities() : array {
		$maxMatch = 0;
		$maxPosition = 0;
		$maxIndex = 0;
		$length = count($this->fragments) - 1;
		for ($i = 0; $i <= $length; $i++) {
			$character = $this->getFirstCharacter($this->fragments[$i]);
			$inString = $this->inString($character);

			if ($inString) {
				$fragmentPosition = $this->getPositionOfMatch($character, $i);
				$currentMatches = $this->getNumberOfMatches($i, $fragmentPosition);

				if ($currentMatches > $maxMatch) {
					$maxMatch = $currentMatches;
					$maxPosition = $fragmentPosition;
					$maxIndex = $i;
				}
			}
		}
		return array(
			"maxIndex" => $maxIndex,
			"maxPosition" => $maxPosition,
			"maxMatch" => $maxMatch
		);
	}

	/**
	 * Merges and unsets the string with the lower match length.
	 *
	 * @param integer $mergeFrom   The fragment index that we will cut data from here
	 * 							   and put into another fragment.
	 * @param integer $mergeInto   The fragment index that we are appending data to.
	 * @param integer $matchLength The length of the matched characters.
	 * @param boolean $significant Determines if a merge is necessary when the $matchLength is one.
	 * @return void
	 */
	public function mergeFragments(int $mergeFrom, int $mergeInto, int $matchLength, bool $significant = TRUE) {
		if ($matchLength <= 1) {
			$significant = $this->isMatchSignificant($mergeFrom, $mergeInto);
		}

		if ($significant) {
			$this->fragments[$mergeFrom] = substr_replace(
				$this->fragments[$mergeFrom],
				"",
				0,
				$matchLength
			);
		}
		
		$this->fragments[$mergeInto] .= $this->fragments[$mergeFrom];
		unset($this->fragments[$mergeFrom]);
		$this->fragments = array_values($this->fragments);
	}

	/**
	 * If the match between two fragments is only one character, verify if this match is significant or
	 * an arbitrary match (single character found randomly in the string).
	 *
	 * @param integer $mergeFrom The fragment index that we will cut data from here
	 * 							 and put into another fragment.
	 * @param integer $mergeInto The fragment index that we are appending data to.
	 * @return boolean
	 */
	public function isMatchSignificant(int $mergeFrom, int $mergeInto) : bool {
		$firstChar = substr($this->fragments[$mergeFrom], 0, 1);
		$lastChar = substr(strrev($this->fragments[$mergeInto]), 0, 1);
		return $firstChar === $lastChar;
	}

	/**
	 * Identifies the most appropriate strings to merge and completes this task until there is only
	 * a single string remaining.
	 *
	 * @return void
	 */
	public function recompileFragments() {
		while (count($this->getFragments()) > 1) {
			$similarities = $this->findSimilarities();
			$this->mergeFragments(
				$similarities["maxIndex"],
				$similarities["maxPosition"],
				$similarities["maxMatch"]
			);
		}
	}

}