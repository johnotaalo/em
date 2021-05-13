<template>
	<div>
		<router-view></router-view>
	</div>
</template>

<script type="text/javascript">
	import VueRouter from 'vue-router'
	Vue.use(VueRouter)

	import FacilityListing from './FacilityListing'
	const router = new VueRouter({
		linkExactActiveClass: 'active',
		routes: [
			{
				path: "/",
				name: "facility-index",
				children: [
				{
	 				path: '/listing',
	 				component: FacilityListing,
	 				name: 'facility-listing'
	 			}
				]
			}
		]
	});
	export default{
		router,
		mouted(){
			console.log("Index View mounted")
		},
		created(){
			console.log("Index View Created");
		}
	}
</script>