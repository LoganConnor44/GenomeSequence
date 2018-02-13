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

	// public function findHighSimilarity() {
	// 	$length = count($this->fragments) - 1;
	// 	$matchCount = 0;

	// 	for ($i = 0; $i <= $length; $i++) {
	// 		$next = $length === $i ? 0 : $i;
	// 		$matchCount = similar_text($this->fragments[$i], $this->fragments[$next]) . PHP_EOL;

	// 		if ($matchCount > 0) {
	// 			$keys = array(
	// 				$i,
	// 				$next
	// 			);
	// 		}
	// 	}
	// 	echo "Index $i and $next are the most compatable.";
	// }

	/**
	 * Creates an array of the beginning and ending character in a string
	 * @param string $fragment The passed in string to analyze
	 * @return array
	 */
	// public function getBeginningAndEnd(string $fragment) : array{
	// 	$fragment = trim($fragment);
	// 	return array(
	// 		"beginning" => substr($fragment, 0, 1),
	// 		"end" => substr($fragment, -1)
	// 	);
	// }

	public function getBeginning(string $fragment) : string {
		$fragment = trim($fragment);
		return substr($fragment, 0, 1);
	}

	public function inString(string $begChar) : bool {
		foreach ($this->fragments as $fragment) {
			$result = strpos($fragment, $begChar);

			if ($result) {
				return TRUE;
			}
		}
		return TRUE;
	}

	/**
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

	/**
	 * Identifies if the given string contains the beginning or end character of a separate string
	 * @param array $beginningAndEnd
	 * @param string $fragment
	 * @return boolean
	 */
	public function containBeginning(array $beginning, string $fragment) : bool {
		$beg = strpos($fragment, $beginning["beginning"]);
		if ($beg) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 */
	// public function getCountOfMatches(array $beginning) : int {
	// 	$nextPosition = 1;
	// 	$charactersToIdentify = 1;
	// 	$charactersFound = 0;

	// 	while (TRUE) {
	// 		$character = substr($fragment, $nextPosition, $charactersToIdentify);
	// 		$beginning["beginning"] .= trim($character);
	// 		var_dump(
	// 			$beginning,
	// 			$fragment
	// 		);
	// 		$charsExist = strpos($fragment, $beginning["beginning"]);

	// 		if ($charsExist === FALSE) {
	// 			return count($charactersFound);
	// 		}

	// 		$nextPosition++;
	// 		$charactersFound++;
	// 	}

	// 	foreach ($this->fragments as $fragment) {
	// 		$character = substr($fragment, $nextPosition, $charactersToIdentify);
	// 	}
	// }

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