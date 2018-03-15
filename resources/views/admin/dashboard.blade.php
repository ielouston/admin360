@extends('layouts.master')

@section('title')
	<p>Admin360 | Administra tu negocio en cualquier dispositivo</p>
@stop
@section('content')
	@include('partials.navs.default')
	<?php	
		$user = Auth::user();
	?>
	<div class="container full">
		<h1>Bienvenido al sistema web,  {{ Auth::user()->name }}!</h1>	
		
		<pre>
			<p>IP : </p>
		</pre>
	</div>
	
@stop