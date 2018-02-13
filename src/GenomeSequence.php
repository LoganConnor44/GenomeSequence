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
	public function getBeginningAndEnd(string $fragment) : array{
		$fragment = trim($fragment);
		return array(
			"beginning" => substr($fragment, 0, 1),
			"end" => substr($fragment, -1)
		);
	}

	/**
	 * Identifies if the given string contains the beginning or end character of a separate string
	 * @param array $beginningAndEnd
	 * @param string $fragment
	 * @return boolean
	 */
	public function containBeginningOrEnd(array $beginningAndEnd, string $fragment) : bool {
		$beg = strrpos($fragment, $beginningAndEnd["beginning"]);
		$end = strrpos($fragment, $beginningAndEnd["end"]);
		if ($beg) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 */
	public function getCountOfMatches(array $beginningAndEnd, string $fragment) : int {
		$nextPosition = 1;
		$charactersToIdentify = 1;
		$charactersFound = 0;

		while (TRUE) {
			$character = substr($fragment, $nextPosition, $charactersToIdentify);
			echo PHP_EOL;
			echo $character;
			echo PHP_EOL;
			$beginningAndEnd["beginning"] .= trim($character);
			$charsExist = strrpos($fragment, $beginningAndEnd["beginning"]);

			if (!$charsExist) {
				return count($charactersFound);
			}

			$nextPosition++;
			$charactersFound++;
		}
	}

	public function recompileFragments() {
		$length = count($this->fragments) - 1;
		for ($i = 0; $i <= $length; $i++) {
			$next = $length === $i ? 0 : $i;
			$beginningAndEnd = $this->getBeginningAndEnd($this->fragments[$i]);

			$hasBegOrEnd = $this->containBeginningOrEnd($beginningAndEnd, $this->fragments[$next]);
			if ($hasBegOrEnd) {
				$matches = $this->getCountOfMatches($beginningAndEnd, $this->fragments[$next]);
			}
		}
	}


















}