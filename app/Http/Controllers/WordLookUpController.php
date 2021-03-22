<?php

namespace App\Http\Controllers;

use App\Graph;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class WordLookUpController extends Controller
{
	public function create()
	{
		$words = explode(' ', request('sentence'));
		$queryStack = [];

		if (count($words) > 1) {
			foreach($words as $key => $word) {
				$next = next($words);
				if (strlen($next) > 0) {
					$queryStack[] = ("
						MERGE (w_${key}:WORD { body: '{$word}'})
						MERGE (w_${key}_next:WORD { body: '{$next}' })
						MERGE (w_${key})-[rel_${key}:NEXT]->(w_${key}_next) 
							ON CREATE SET 
								rel_${key}.count = 1
							ON MATCH SET 
								rel_${key}.count = rel_${key}.count + 1
					");
				}
			}
		}

		Graph::run(join("", $queryStack));
	}

    public function show($word)
    {
		$words = explode(" ", $word);
		
		if (count($words) == 1) {
			$word = $words[0];
		}

		if (count($words) > 1) {
			$word = $words[count($words) - 1];
		}

    	$query = "
			MATCH
			   	(nextWord:WORD)<-[:NEXT]-(w:WORD)
			   	WHERE w.body CONTAINS '{$word}'
			   	return nextWord
    	";

		$results = [];

    	foreach(Graph::run($query) as $result) {
			$results[] = Arr::get($result->get('nextWord'), 'body');
		}

		return collect($results)->unique()->values()->all();
    }
}
