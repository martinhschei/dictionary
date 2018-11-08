<?php

namespace App\Http\Controllers;

use Graph\Graph;

class WordLookUpController extends Controller
{
	public function create()
	{
		$graph = new Graph();
		$stack = $graph->stack();
		$words = explode(' ', request('text'));

		foreach($words as $word) {
			$next = next($words);
			$stack->push("
				MERGE (w:WORD {body: '{$word}'})
				MERGE (w_next:WORD {body: '{$next}'})
				MERGE (w)-[rel:BEFORE]->(w_next)
					ON CREATE SET rel.count = 1
					ON MATCH SET rel.count = rel.count + 1
			");
		}
		$graph->runStack($stack);
	}

    public function show($word)
    {
    	$graph = new Graph();
    	$query = "
			MATCH
			   	(wBefore:WORD)-[:BEFORE]->(w:WORD)
			   	where w.body contains '{$word}'
			   	return wBefore
    	";
    	$result = $graph->run($query);
    	dd($result);
    }
}
