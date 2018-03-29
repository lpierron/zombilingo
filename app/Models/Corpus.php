<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Gwaps4nlp\Models\Source;
use Gwaps4nlp\Models\Corpus as Gwaps4nlpCorpus;

class Corpus extends Gwaps4nlpCorpus
{
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function bound_corpora()
	{
	  return $this->belongsToMany('App\Models\Corpus','preannotated_reference_corpus','corpus_id','reference_corpus_id');
	}

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function evaluation_corpora()
	{
	  return $this->belongsToMany('App\Models\Corpus','preannotated_evaluation_corpus','corpus_id','evaluation_corpus_id');
	}
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function evaluated_corpus()
	{
	  return $this->belongsToMany('App\Models\Corpus','preannotated_evaluation_corpus','evaluation_corpus_id','corpus_id');
	}
	
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function annotations()
	{
	  return $this->hasManyThrough('App\Models\Annotation','App\Models\Sentence');
	}	

	/**
	 * 
	 *
	 * @return boolean
	 */
	public function isReference()
	{
	  return $this->source_id == Source::getReference()->id;
	}

	/**
	 * 
	 *
	 * @return boolean
	 */
	public function isPreAnnotated()
	{
	  return $this->source_id == Source::getPreAnnotated()->id;
	}

	/**
	 * 
	 *
	 * @return boolean
	 */
	public function isPreAnnotatedForEvaluation()
	{
	  return $this->source_id == Source::getPreAnnotatedForEvaluation()->id;
	}

	/**
	 * 
	 *
	 * @return boolean
	 */
	public function getControlCorpus()
	{
		$corpus_id = $this->id;
	  	return Corpus::where('source_id',1)->whereExists(function ($query) use ($corpus_id) {
                $query->select(DB::raw(1))
                      ->from('annotations as a_control')->join('annotations as a_parser',function ($join) {
				            $join->on('a_parser.sentence_id', '=', 'a_control.sentence_id');
				            $join->on('a_parser.word_position', '=', 'a_control.word_position');
				        })
                      ->whereRaw('a_control.corpus_id = corpuses.id')->whereRaw('a_control.source_id = 1')
                      ->whereRaw('a_parser.corpus_id = '.$corpus_id)->whereRaw('a_parser.source_id = 5');

            })->first();
	}
}
