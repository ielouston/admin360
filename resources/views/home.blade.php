@extends('layouts.default')

@section('content')
	
	<div class="container">
		<h1>Bienvenido a Mueblerias Rangel</h1>
		<div class="block">
			<img src="" alt="">
			<gallery
				source="/api/productos"
				business_id="0">
				
			</gallery>	
		</div>
	</div>
	
@stop

