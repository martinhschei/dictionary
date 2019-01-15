<?php

namespace App\Http\Controllers;

use Graph\Graph;
use App\Http\Controllers\Controller;

class DictionaryController extends Controller
{
	public function create()
	{
		$graph = new Graph();
		$stack = $graph->stack();
		$words = explode(' ', request('text'));
		foreach($words as $word) {
			$next = next($words);
			if (strlen($next) != 0) {
				$stack->push("
					MERGE (w:WORD { body: '{$word}' })
					MERGE (w_next:WORD { body: '{$next}' })
					MERGE (w)-[rel:BEFORE]->(w_next)
						ON CREATE SET rel.count = 1
						ON MATCH SET rel.count = rel.count + 1
				");
			}
			$stack->push("
				MERGE (w:WORD { body: '{$word}' })
			");
		}
		$graph->runStack($stack);
	}

    public function show($word)
    {
    	$graph = new Graph();
    	
    	$query = "
			MATCH
			   	(w:WORD)-[rel:BEFORE]->(wordBefore:WORD)
			   	WHERE toLower(w.body) STARTS WITH toLower('{$word}') 
			   	return wordBefore.body as word, rel.count as count, w.body as spelling
    	";

    	return collect($graph->run($query)->raw()->records())->map(function($word) {
    		return [
    			'word' => $word->value('word'),
    			'count' => $word->value('count'),
    			'spelling' => $word->value('spelling'),
    		];
    	});
    }
}
