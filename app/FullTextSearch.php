<?php

namespace App;

trait FullTextSearch
{
	/**
	 * Replaces spaces with full text search wildcards
	 *
	 * @param string $term
	 * @return string
	 */
	protected function fullTextWildcards($term)
	{

        $newTerm = str_replace("'", '"', $term);
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $newTerm, $matches); // split by spaces, but keeping quoted strings with ""
        $queryString = '';
        foreach ($matches[0] as $match) {
            if (substr($match, -1) !== '"') $match = $match . "*";
            $queryString = $queryString . " +" . $match;
        }
	    return $queryString;
		// return str_replace(' ', '*', $term) . '*';
	}

	/**
	 * Scope a query that matches a full text search of a term.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string $term
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeSearch($query, $term)
	{
		$columns = implode(',',$this->searchable);

		$query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $this->fullTextWildcards($term));

		return $query;
	}
}