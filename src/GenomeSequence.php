<?php
namespace AderantChallenge;

class GenomeSequence {

	private $filePath;

	private $fragments;

	public function __construct(string $filePath) {
		$this->filePath = $filePath;
	}

	public function setFragments() : GenomeSequence {
		$handle = fopen($this->filePath, "r");
		if ($handle) {
		    while (($buffer = fgets($handle, 4096)) !== false) {
		        $this->fragments[] = $buffer;
		    }
		    fclose($handle);
		}
		return $this;
	}

	public function getFragments() : array {
		return $this->fragments;
	}	

	/**
	 * Returns the first character in a given string.
	 * 
	 * @param string $fragment The given String.
	 * @return string
	 */
	public function getBeginning(string $fragment) : string {
		$fragment = trim($fragment);
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
	public function getPositionOfChar(string $character, int $excludeKey) : int {
		$fragments = $this->fragments;
		unset($fragments[$excludeKey]);

		foreach ($fragments as $index => $fragment) {
			$result = strpos($fragment, $character);

			if ($result) {
				return $index;
			}
		}
	}

	public function recompileFragments() {
		$length = count($this->fragments) - 1;
		for ($i = 0; $i <= $length; $i++) {
			$next = $length === $i ? 0 : $i + 1;
			$beginning = $this->getBeginningAndEnd($this->fragments[$i]);

			$hasBegOrEnd = $this->containBeginning($beginning, $this->fragments[$next]);
			if ($hasBegOrEnd) {
				$matches = $this->getCountOfMatches($beginning);
			}
		}





		$length = count($this->fragments) - 1;
		for ($i = 0; $i <= $length; $i++) {
			$next = $length === $i ? 0 : $i + 1;

			$character = $this->getBeginning($this->fragments[$i]);
			$inString = $this->inString($character);
			if ($inString) {
				$position = $this->getPositionOfChar($character, $excludeKey);

				//array ($position => $numberOfMatchingChars)
				$matches = $this->getNumberOfMatch($position);
			}

		}



	}


















}