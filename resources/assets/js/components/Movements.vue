<template>
	<div class="container">
		<div class="dv-body wraper">
			<h1>Movimientos</h1>
			<hr>
			<div class="row">
				<div class="col-md-2">
					<input type="date" name="date_from" v-model="date_from" @keyup.enter="loadMovements(false)" class="form-control">
				</div>
				<div class="col-md-2">
					<input type="date" name="date_to" v-model="date_to" @keyup.enter="loadMovements(true)" class="form-control">
				</div>
				<div class="col-md-4">
					<select name="" v-model="business_id" @change="loadMovements" class="form-control">
						<option value="0">Todos</option>
						<option value="1">Muebleria Fraydé</option>
						<option value="2">Muebleria Castañon</option>
						<option value="3">Expo Muebles</option>
					</select>
				</div>
				<div class="col-md-4">
					<button class="btn btn-primary" @click="loadDateModal">
						<i class="glyphicon glyphicon-glyphicon-calendar"></i>Editar fecha
					</button>
				</div>
			</div>
			<hr>
			<table class="dv-table table table-collapse">
				<thead>
					<tr class="mov">
						<th class="mov-nota">nota</th>
						<th class="mov-nombre">mov</th>
						<th class="mov-entradas">monto</th>
						<th class="mov-saldo">saldo</th>
						<th class="mov-cliente">cliente</th>
						<th class="mov-existAnt">exist. ant.</th>
						<th class="mov-existAct">exist. act.</th>
						<th class="mov-dates">fecha</th>
						<th class="mov-estado">estado</th>
					</tr>	
				</thead>
				
				<tbody>
					<tr :id="mov.id" class="mov link link-modal" v-for="mov in movs_list" @click="loadModal(true, $event)" title="false">
						<td class="mov-nota">{{mov.nota}}</td>
						<td class="mov-nombre">{{mov.movimiento}}</td>
						<td class="mov-entradas">{{mov.entradas}}</td>
						<td class="mov-saldo">{{mov.saldo}}</td>
						<td class="mov-cliente">{{mov.cliente}}</td>
						<td class="mov-existAnt">{{mov.exist_anterior}}</td>
						<td class="mov-existAct">{{mov.exist_actual}}</td>
						<td class="mov-date" v-if="formattedDate(mov, mov.fecha)">
						{{mov.fecha_formateada}}
						</td>
						<td class="mov-estado">{{ mov.situacion }}</td>
					</tr>
				</tbody>
			</table>
			<!-- modal vue js -->
			<transition name="modal">
			    <div class="modal-mask" v-if="showModal">
			      <div class="modal-wrapper">
			        <div class="modal-container">

			          <div class="modal-header">
			            <slot name="header">
			              Detalles del movimiento
			            </slot>
			          </div>

			          <div class="modal-body">
			            <slot name="body">
			              <div class="mov-refs">
			              	<section class="mov-ref-sale" v-if="showSale">
			              		<div class="row">
			              			<div class="col-md-6">
			              				<button class="btn btn-primary btn-block">Ver Pagos</button>
			              			</div>
			              			<div class="col-md-6">
			              				<button class="btn btn-secondary btn-block" @click="regenerateMovs">Regenerar movs.</button>
			              			</div>
			              		</div>
			              		<div class="form-group">
			              			<h4><b>{{saleProfile.nota}} : {{saleProfile.tipo_venta}}</b></h4>
			              			<h3>{{saleProfile.cliente}}</h3>
			              			<div class="row">
			              				<div class="col-md-4">
			              					<p><b>Subtotal : </b> {{saleProfile.subtotal}}</p>
			              				</div>
			              				<div class="col-md-4">
			              					<p><b>Descuento : </b> {{saleProfile.descuento}}</p>
			              				</div>
			              				<div class="col-md-4">
			              					<p><b>Total : </b> {{saleProfile.total}}</p>
			              				</div>
			              			</div>
			              			
			              			<div class="row">
			              				<div class="col-md-4">
			              					<p><b>Anticipo : </b> {{saleProfile.anticipo}}</p>
			              				</div>

			              				<div class="col-md-4">
			              					<p><b>Pagado : </b> {{saleProfile.pagado}}</p>
			              				</div>

			              				<div class="col-md-4">
			              					<p><b>Saldo : </b> {{saleProfile.saldo_actual}}</p>
			              				</div>
			              			</div>
			              			
			              			<div class="row">
			              				<div class="col-md-6">
			              					<p><b>Inversión : </b> {{saleProfile.inversion}}</p>
			              				</div>

			              				<div class="col-md-6">
			              					<p><b>Ganancias : </b> {{saleProfile.pagado - saleProfile.inversion}}</p>
			              				</div>
			              			</div>
			              		</div>
			              	</section>

			              	<section class="mov-ref-prod" v-if="showProduct">
			              		<div class="row">
			              			<div class="col-md-6">
			              				<button class="btn btn-primary btn-block">Ver Movimientos</button>
			              			</div>
			              			<div class="col-md-6">
			              				<button class="btn btn-secondary btn-block">Editar</button>
			              			</div>
			              		</div>
			              		<div class="form-group">
			              			<img :src="productProfile.product.avatar" alt="Foto del producto">
			              			<h4><b>{{productProfile.product.clave}} : {{productProfile.product.claves_aux}}</b></h4>
			              			<h3>{{productProfile.product.nombre}}</h3>
			              			
			              			<p><b>Descripcion : </b> {{productProfile.descripcion}}</p>
			              			<div class="row">
			              				<div class="col-md-6">
			              					<p><b>Precio compra : </b> {{productProfile.product.precio_compra}}</p>
			              				</div>
			              				<div class="col-md-6">
			              					<p><b>Precio contado : </b> {{productProfile.product.precio_contado}}</p>
			              				</div>
			              			</div>
			              			
			              			<div class="row">
			              				<div class="col-md-6">
			              					<p><b>Precio oferta : </b> {{productProfile.product.precio_oferta}}</p>
			              				</div>
			              				<div class="col-md-6">
			              					<p><b>Precio mayoreo : </b> {{productProfile.product.precio_mayoreo}}</p>
			              				</div>
			              			</div>
			              			
			              			<div class="row">
			              				<div class="col-md-4">
			              					<p><b>Iva : </b> {{productProfile.product.iva}}</p>
			              				</div>
			              				<div class="col-md-4">
			              					<p><b>Existencia : </b>{{productProfile.existencia}}</p>
			              				</div>
			              				<div class="col-md-4">
			              					<p><b>Stock : </b>{{productProfile.stock}}</p>
			              				</div>
			              			</div>
			              						              			
			              			<p><b>Linea : </b>{{productProfile.product.linea}}</p>
			              			
			              			<div class="row">
			              				<div class="col-md-6">
			              					<p><b>Comprados : </b> {{productProfile.comprados}}</p>
			              				</div>

			              				<div class="col-md-6">
			              					<p><b>Vendidos : </b> {{productProfile.vendidos}}</p>
			              				</div>
			              			</div>
			              			<div class="row">
			              				<div class="col-md-6">
			              					<p><b>Situación : </b> {{productProfile.situacion}}</p>
			              				</div>

			              				<div class="col-md-6">
			              					<p><b>Usuario : </b>{{productProfile.usuario}}</p>
			              				</div>
			              			</div>
			              			
			              			
			              		</div>
			              	</section>

			              	<section class="mov-ref-buy" v-if="showBuy">
			              		<div class="row">
			              			<div class="col-md-6">
			              				<button class="btn btn-primary btn-block">Ver Proveedor</button>
			              			</div>
			              			<div class="col-md-6">
			              				<button class="btn btn-danger btn-block">Cancelar</button>
			              			</div>
			              		</div>

			              		<div class="form-group">
			              			<h3><b>{{buyProfile.clave}}</b></h3>
			              			<h4><b>{{buyProfile.nota}} : {{buyProfile.tipo_compra}}</b></h4>
			              			<p><b>Total : </b> {{buyProfile.total}}</p>
			              			<p><b>Usuario : </b> {{buyProfile.usuario}}</p>
			              			<p><b>Situación : </b> {{buyProfile.situacion}}</p>
			              		</div>
			              	</section>

			              	<section class="mov-ref-pay">
			              		
			              	</section>
			              </div>
			            </slot>
			          </div>

			          <div class="modal-footer">
			            <slot name="footer">
			              <button class="modal-default-button" @click="shutdown">
			                Cerrar
			              </button>
			            </slot>
			          </div>
			        </div>
			      </div>
			    </div>
			</transition>
			
			<!-- DatePicker -->
			<date-picker :valueDate="new_date" :show="showDateModal" @dateUpdated="loadMovements" @close="showDateModal = false" :movs="movs_selected" @reset="resetSelecteds"></date-picker>
		</div>
	</div>
</template>

<script>
	import Vue from 'vue'
	import axios from 'axios'
	import DatePicker from './datepicker/Datepicker.vue'

	export default{
		props : ['source', 'token'],
		created(){
			this.setDate(),
			this.setToken(),
			this.loadMovements()
		},
		data(){
			return {
				date_from: '',
				date_to: '',
				new_date: '',
				hour: '', 
				business_id: 0,
				movs_selected : ['0'],
				movs_list: {},
				movement: {
					business_id: 0,
					buy_id: 0,
					cliente: '',
					comentarios: '',
					nota: 0,
					movimiento: '',
					entradas: 0,
					salidas: 0,
					exist_actual: 0,
					exist_anterior: 0,
					saldo: 0,
					situacion: '',
					updated_at: '',
					usuario: '',
					expense_id: 0,
					fecha: '',
					fecha_formateada: '',
					id: 0,
					sale_id : 0,
					buy_id : 0,
					product_id : 0,
					payment_id : 0,
					inventory_id : 0,
					selected : false
				},
				both : false,
				showModal: false,
				showDateModal: false,
				showSale: false,
				showProduct: false,
				showBuy: false,
				detailSources: {
					sale: '/api/ventas',
					product: '/api/productos',
					buy: '/api/compras',
					movement: '/api/movimientos'
				},
				saleProfile : {
					id : 0,
					nota : 0,
					cliente : '',
					client_id : 0,
					subtotal : 0,
					descuento : 0,
					anticipo : 0,
					total : 0,
					pagado : 0,
					inversion : 0,
					saldo_actual : 0,
					fecha : '',
					hora : '',
					situacion : '',
					usuario : '',
					updated_at : ''
				},
				productProfile: {
					id : 0,
					business_id : 0,
					existencia : 0,
					stock : 0,
					nombre : '',
					descripcion : '',
					provider_id : 0,
					vendidos : 0,
					comprados : 0,
					situacion : '',
					usuario : '',
					updated_at : '',
					product : {
						nombre : '',
						descripcion : '',
						clave : '',
						claves_aux : '',
						precio_compra : 0,
						precio_contado : 0,
						precio_oferta : 0,
						precio_mayoreo : 0,
						iva : 0,
						situacion : '',
						linea : ''
					}
				},
				buyProfile :{
					id : 0,
					nota : 0,
					provider_id : 0,
					business_id : 0,
					tipo_compra : '',
					total : 0,
					fecha : '',
					hora : '',
					productos : '',
					situacion : '',
					usuario : '',
					comentarios : ''
				}
			}
		},
		components:{
			'date-picker' : DatePicker
		},
		methods: {
			shutdown(){
				this.showSale = false
				this.showProduct = false
				this.showBuy = false
				this.showModal = false
			},
			setToken(){
				axios.defaults.headers.common = {
					'Accept': 'application/json',
					'Authorization' : 'Bearer ' +this.token
				};
			},
			setDate(){
				var moment = require('moment');
				var date = moment().format('YYYY-MM-DD');
				this.date_from = date;
				this.date_to = date;
				this.new_date = date;
			},
			getMovements(){
				var vm = this
				let movs_selected = []

				$.each(vm.movs_list, function(key, mov) {
				     if(mov.selected){
				     	movs_selected.push(mov);
				     }
				});
				// for (var mov of vm.movs_list) {
				// 	console.log(mov)
				// }

				vm.movs_selected = movs_selected
				return movs_selected
			},
			regenerateMovs(){
				let vm = this
				let url = `${this.detailSources.sale}/movs?no_params=true`
				let body = {
					sale : vm.saleProfile
				}

				axios.post(url, body)
					.then(function(response){
						vm.loadMovements()
					})
					.catch(function(response){
						console.log(response)
					})
			},
			resetSelecteds(){

				var vm = this

				$.each(vm.movs_list, function(key, mov){

					//if the movement it's selected
					if(mov.selected){
						mov.selected = false;
						var htmlMov = $('#'+ mov.id)
						$('#' + mov.id).attr('title', 'false')
						htmlMov.removeClass('link-pressed')

					}
				})
			},
			loadMovements(both = false){
				this.both = both
				var url = `${this.source}?date_from=${this.date_from}&date_to=${this.date_to}&both=${this.both}&business_id=${this.business_id}`

				var vm = this
				

				axios.get(url)
					.then(function(response){
						vm.resetSelecteds()
						Vue.set(vm.$data, 'movs_list', response.data)
					})
					.catch(function(response){
						console.log(response)
					})

			},
			toggleMovement(event){
				
				var target = $(event.currentTarget)
				var id_mov = target.context.id
				var vm = this
				var toggle = target.context.title
				toggle = toggle == "false" ? "true" : "false"
				target.context.title = toggle
				var classes = target.context.className

				if(toggle == "true"){
					target.context.className += " link-pressed"
					vm.getMove(id_mov, true)
					// vm.movs_list[id_mov].selected = true
				}
				else{
					$('#'+id_mov).removeClass('link-pressed')
					vm.getMove(id_mov, false)
					// vm.movs_list[id_mov].selected = false
				}

			},
			getMove(id, select = true){
				var vm = this
				$.each(vm.movs_list, function(key, mov){
					if(id == mov.id){
						vm.movement = mov
						mov.selected = select ? true : false
					}
				})
			},
			loadModal(show, event){
				
				var vm = this;
				if(event.ctrlKey)
				{
					vm.toggleMovement(event);
					return;
				}
				vm.resetSelecteds();
				
				var target = $(event.currentTarget);
				var id_mov = target.context.id;
				vm.getMove(id_mov)
				
				switch(this.movement.movimiento.substr(0, 1)){
					case "V":
					case "A":
						var url = `${this.detailSources.sale}/${this.movement.sale_id}?no_params=true`

						axios.get(url)
						.then(function(response){
							Vue.set(vm, 'saleProfile', response.data)
							vm.showSale = true
						})
						.catch(function(response){
							console.log(response)
						})
						
						break;
					case "I":
						
						var url = `${this.detailSources.product}/${this.movement.product_id}/${this.movement.business_id}?no_params=true`

						axios.get(url)
							.then(function(response){
								Vue.set(vm, 'productProfile', response.data)
								vm.showProduct = true
							}) 
							.catch(function(response){
								console.log(response)
							})
						break;
					case "C":
						
						var url = `${this.detailSources.buy}/${this.movement.buy_id}?no_params=true`

						axios.get(url)
							.then(function(response){
								Vue.set(vm, 'buyProfile', response.data)
								vm.showBuy = true
							}) 
							.catch(function(response){
								console.log(response)
							})
						break;
				}
				vm.showModal = show
			},
			loadDateModal(){
				var vm = this
				var movs_selected = vm.getMovements()
				vm.showDateModal = true
				
			},
			formattedDate(mov, date){
				var moment = require('moment');
				
				mov.fecha_formateada = moment(date).format('DD/MM/YYYY hh:mm a')
				
				return true
			}
		}
	}
</script>