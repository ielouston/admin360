<template>
	<div class="form-group">
		<h3>{{sale.cliente}}</h3>
		<h4>{{sale.nota}} : {{sale.tipo_venta}}</h4>
		<p><b>Subtotal : </b> {{sale.subtotal}}</p>
		<p><b>Descuento : </b> {{sale.descuento}}</p>
		<p><b>Total : </b> {{sale.total}}</p>
		<p><b>Anticipo : </b> {{sale.anticipo}}</p>
		<p><b>Pagado : </b> {{sale.pagado}}</p>
		<p><b>Saldo : </b> {{sale.saldo_actual}}</p>
		<p><b>Inversión : </b> {{sale.saldo_actual}}</p>
		<p><b>Ganancias : </b> {{sale.pagado - sale.inversion}}</p>

		<div class="form-group">
			<div class="col-md-6">
				<button class="btn btn-primary btn-block">Ver Pagos</button>
			</div>
			<div class="col-md-6">
				<button class="btn btn-secondary btn-block">Editar</button>
			</div>
		</div>
	</div>
</template>

<script>

import Vue from 'vue'
import axios from 'axios'


export default{
	props : ['sale'],
	data(){
		return{

		}
	},
	created(){

	},
	methods: {

	}
}
</script>