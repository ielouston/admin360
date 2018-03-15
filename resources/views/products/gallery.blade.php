@extends('layouts.master')


@section('nav')
	@include('partials.navs.client')
@stop

@section('content')
	<div class="container">
		<gallery source="/api/productos"
				 business_id="0">
		</gallery>
	</div>
@stop