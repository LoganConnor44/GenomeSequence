<?php
namespace AderantChallenge;

/**
 * A class to reconstruct fragmented text strings back into an original document.
 */
class GenomeSequence {

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
	 * @return GenomeSequence
	 */
	public function setFragments() : GenomeSequence {
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
	 * @param string  $fragment 	 The given string.
	 * @param boolean $reverseString Value to tell method to reverse the given string.
	 * @return string
	 */
	public function getFirstCharacter(string $fragment, bool $reverseString = FALSE) : string {
		if ($reverseString) {
			$fragment = strrev($fragment);
		}
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
	 * @param integer $source	  The index of the original fragment.
	 * @param integer $matchIndex The index of the fragment being compared.
	 * @return integer
	 */
	public function getNumberOfMatches(int $source, int $matchIndex) : int {
		$start = 0;
		$length = 1;
		$numberOfMatches = 0;
		$characters = "";

		while (TRUE) {
			$builder = substr($this->fragments[$source], $start, $length);
			$characters .= $builder;
			$charsExist = strpos($this->fragments[$matchIndex], $characters);
			if (!$charsExist || strlen($characters) === strlen($this->fragments[$source])) {
				return $numberOfMatches;
			}
			$start++;
			$numberOfMatches++;
		}
	}

	/**
	 * Iterates through each stringed-array and does a comparision of the most similar text files.
	 * Identifies the most appropriate strings to merge and sets these as 'max' values.
	 *
	 * NOTE: This approach only works if the fragments are in "order" (fragment index 0 is the
	 * 			beginning of the original document and the max index is the last fragment), if the
	 * 			fragments are randomized a comparison of the last character will need to be created
	 * 			(we are currently only doing the beginning).
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
	 * Merges and unsets the string with the higher index.
	 *
	 * @param integer $mergeFrom   The fragment index that we will cut data from here
	 * 							   and put into another fragment.
	 * @param integer $mergeInto   The fragment index that we are appending data to.
	 * @param integer $matchLength The length of the matched characters.
	 * @return void
	 */
	public function mergeFragments(int $mergeFrom, int $mergeInto, int $matchLength) {
		$directMatch = $this->isDirectMatch(
			$this->fragments[$mergeFrom],
			$this->fragments[$mergeInto],
			$matchLength
		);

		if ($directMatch) {
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
	 * Verifies if the match that is passed is an arbitrary or direct match.
	 *
	 * Arbitrary Match : thiswillnot - matchatall
	 *		The first "t" will find the other t's in 'matchatall' but have no
	 * 		other similarities.
	 * Direct Match    : indy - andindy
	 *
	 * @param string  $mergeFrom   Adding Better description when fully vetted.
	 * @param string  $mergeInto   Adding Better description when fully vetted.
	 * @param integer $matchLength Adding Better description when fully vetted.
	 * @return boolean
	 */
	public function isDirectMatch(string $mergeFrom, string $mergeInto, int $matchLength) : bool {
		$beginningFromFrag = substr($mergeFrom, 0, 2);
		$bool = preg_match("[$beginningFromFrag]", $mergeInto);
		if ($bool !== 1) {
			return FALSE;
		}
		return true;
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