@extends('front.template')

@section('main')
<div id="shop" class="container-fluid p-5 text-center">
	<h1>Boutique</h1>
    <div class="row">
        <div id="caddy" class="col-lg-4">

        </div>
        <div id="result" class="col-lg-4 text-center">
        @if(count($games_in_progress))
            {{ trans('shop.game-in-progress-1') }}<br/>
            {{ trans('shop.game-in-progress-2') }}<br/>
            {{ trans('shop.game-in-progress-3') }}<br/>
            {{ trans('shop.game-in-progress-4') }}<br/>
           @foreach ($games_in_progress as $game_in_progress)
        		@if($game_in_progress->relation->type=='special')
           			- {!! link_to('game/special/begin/'.$game_in_progress->relation->id.'?corpus_id='.$game_in_progress->corpus->id,$game_in_progress->relation->name.' (corpus '.$game_in_progress->corpus->name.')') !!}<br/>
            	@else
					- {!! link_to('game/game/begin/'.$game_in_progress->relation->id.'?corpus_id='.$game_in_progress->corpus->id,$game_in_progress->relation->name.' (corpus '.$game_in_progress->corpus->name.')') !!}<br/>
            	@endif
           @endforeach
        @endif
        </div>
        <div id="purse" class="col-lg-4">
            <h2>
            	{!! Html::image('img/piece.png','money') !!}
            	<span id="money" class="money">{{ $game->user->money}}</span>
            </h2>
        </div>
    </div>

	@foreach ($game->inventaire() as $object)
		<?php
			$price = ($game->isInProgress())?$object->price_ingame:$object->price;
		?>
		<div class="object row" data-object-id="{{$object->id}}">
			<div class="col-12">		
				<div class="image">
					{!! Html::image('img/object/'.$object->image,trans('game.name-'.$object->slug)) !!}
				</div>
				<div>
					<span class="name">
						{{ trans('game.name-'.$object->slug) }}
					</span><br />
					<span class="description">
						{!! Html::image('img/piece.png','prix') !!} 
						{{ $price }}
					</span><br />
					@if(Auth::user()->money < $price)
						<span class="error" data-object-id="{{ $object->id }}">{{ trans('shop.not-enough-money') }}</span>
					@else
						<button class="buy btn btn-success" url="{{ url('game/buyObject') }}" data-object-id="{{ $object->id }}">
							{{ trans('shop.buy') }}
						</button>
						<span class="error" data-object-id="{{ $object->id }}"></span>
					@endif
					<br />
					{{ trans('shop.in-inventory') }} : 
					<span class="owned" data-object-id="{{ $object->id }}">
						{{ $object->quantity }}
					</span><br />
					{{ trans('game.description-'.$object->slug) }}
				</div>
			</div>
		</div>
	@endforeach
</div>
@stop
@section('scripts')
<script>
    $(document).on('click', '.buy', function(){
        object_id = $(this).attr('data-object-id');
        $.ajax({
            url : base_url + 'game/buyObject/' + object_id,
            success : processInventaire
        });
    });
</script>
@stop