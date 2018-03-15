<template>
	<div class="gallery">
		<h1>Galería de muebles</h1>
		<hr>
		<div class="categories">
			<div class="categorie" v-for="cat in categories_list">
				{{ cat }}
			</div>
		</div>
		<div class="products">
			<div class="prod" v-for="prod in prods_list">
				<div class="avatar" :style="'background : url ('+ prod.avatar +')'">
				</div>
				<h3>{{ prod.nombre }}</h3>
				<span class="prod-clave">{{ prod.clave }}</span>

				<div class="row">
					<div class="col-md-6">
						<span class="prod-precio">${{ prod.precio_contado }}</span>
					</div>
					<div class="col-md-6">
						
					</div>
				</div>
			</div>
		</div>
	</div>

</template>
	
<script>
	import Vue from 'vue'
	import axios from 'axios'
	import VueRouter from 'vue-router'

	Vue.use(VueRouter)
	Vue.prototype.$http = axios

	export default {
		props : ['business_id', 'source', 'token'],
		data(){
			return {
				prods_list : {},
				categories_list : {}
			}
		},
		created(){
			this.fetchProducts()
			this.setToken()
		},
		methods: {
			setToken(){
				axios.defaults.headers.common = {
					'Accept': 'application/json',
					'Authorization': 'Bearer ' + this.token
				}
			},
			fetchProducts(){
				var url = `${this.source}/listar/`+ this.business_id + `?no_params=true`
				var vm = this

				axios.get(url)
					.then(function(response){
						Vue.set(vm.$data, 'prods_list', response.data)
					})
					.catch(function(response){
						console.log(response)
					})
			},
			getCategories(){
				var url = `${this.source}?no_params=true`

				var vm = this

				axios.get(url)
					.then(function(response){
						Vue.set(vm.$data, 'categories_list', response.data)
					})
					.catch(function(response){
						console.log(response)
					})
			}
		}
	}
</script>