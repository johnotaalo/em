<template>
	<div class="row">
		<div class="col-md-12">
			<div>
				<b-card title="Facilities">
					<b-table striped bordered small :fields = "fields" :items = "items" :busy="isBusy">
						<div slot="table-busy" class="text-center text-danger my-2">
							<b-spinner class="align-middle"></b-spinner>
							<strong>Loading...</strong>
						</div>

						<template slot="HEAD_facilities" slot-scope="data">
							{{ data.label }}<br/><small style="font-size: 80% !important;">Click on number to view facilities</small>
						</template>

						<template slot="HEAD_baseline" slot-scope="data">
							<center>{{ data.label }}</center>
						</template>

						<template slot="HEAD_End-Term" slot-scope="data">
							<center>{{ data.label }}</center>
						</template>

						<template slot="HEAD_Mid-Term" slot-scope="data">
							<center>{{ data.label }}</center>
						</template>

						<template slot="HEAD_facility_supervisions" slot-scope="data">
							<center>{{ data.label }}</center>
						</template>

						<template slot-scope="row" slot="facilities">
							<b-link @click="row.toggleDetails">{{ row.item.facilities.length }}</b-link>
						</template>

						<template slot="row-details" slot-scope="data">
							<b-card>

								<div v-for="(facilities, subcounty) in countyListing[data.item.county]">
									{{ subcounty }} <b-link v-b-toggle="'collapse-facilities-'+subcounty">{{ facilities.length }} Facilities</b-link>
									<b-collapse :id = "'collapse-facilities-'+subcounty">
										<ol>
											<li v-for="facility in facilities">{{ facility.FACILITY_NAME }}</li>
										</ol>
									</b-collapse>
								</div>
								
								
							</b-card>
						</template>

						<template slot-scope="data" slot="sub_counties">
							{{ $_.keys(countyListing[data.item.county]).length }}
						</template>

						<template slot-scope="data" slot="baseline">
							<center>
								<i v-if="assessmentInfo[data.item.county][1] > 0" class = "fe fe-check text-success"></i>
								<i v-else class = "fe fe-x text-danger"></i>
							</center>
						</template>

						<template slot-scope="data" slot="Mid-Term">
							<center>
								<i v-if="assessmentInfo[data.item.county][2] > 0" class = "fe fe-check text-success"></i>
								<i v-else class = "fe fe-x text-danger"></i>
							</center>
						</template>

						<template slot-scope="data" slot="facility_supervisions">
							<center>
								<i v-if="assessmentInfo[data.item.county][3] > 0" class = "fe fe-check text-success"></i>
								<i v-else class = "fe fe-x text-danger"></i>
							</center>
						</template>

						<template slot-scope="data" slot="End-Line">
							<center>
								<i v-if="assessmentInfo[data.item.county][4] > 0" class = "fe fe-check text-success"></i>
								<i v-else class = "fe fe-x text-danger"></i>
							</center>
						</template>
					</b-table>
				</b-card>
			</div>
		</div>
	</div>
</template>

<script>
	import _ from 'lodash';
	Object.defineProperty(Vue.prototype, '$_', { value: _ });
	export default {
		data() {
			return {
				fields: [ {key: "county", sortable: true}, "facilities", "sub_counties", "baseline", "facility_supervisions",  "Mid-Term","End-Line"],
				items: [],
				isBusy: true
			}
		},
		mounted() {
			this.getCountyFacilityData();
		},
		methods: {
			getCountyFacilityData: function(){
				this.isBusy = true
				axios.get('/api/data/county-facilities-supervisions')
				.then(res => {
					this.isBusy = false
					this.items = res.data
					// var counties = [];
					// _.forOwn(this.items, (item) => {
					// 	counties[item.county] = []
					// 	console.log(item.county)
					// 	_.forOwn(item.facilities, (facility) => {
					// 		if (typeof counties[item.county][facility.SUB_COUNTY] == "undefined") {
					// 			counties[item.county][facility.SUB_COUNTY] = [];
					// 		}

					// 		counties[item.county][facility.SUB_COUNTY].push(facility);
					// 		console.log(counties)
					// 	})
					// })
					// this.countyListing = counties
				})
				.catch(() => {
					this.isBusy = false
					alert("There was an error while running your request");
				})
			},
			// assessmentInfo: function(type, county){

			// }
		},
		computed: {
			assessmentInfo: function(){
				var info = [];
				var counties = this.items;

				if(counties.length > 0){
					_.forOwn(counties, (county) => {
						if(county.supervisions.length > 0){
							_.forOwn(county.supervisions, (supervision) => {
								if(typeof info[county.county] == "undefined"){
									info[county.county] = {};
								}

								if(typeof info[county.county][supervision.assessment_type.id] == "undefined"){
									info[county.county][supervision.assessment_type.id] = 1;
								}else{
									var originalNo = info[county.county][supervision.assessment_type.id];

									var newNo = originalNo + 1
									info[county.county][supervision.assessment_type.id] = newNo;
								}
								
							})
						}else{
							info[county.county] = [];
						}
					})
				}

				return info
			},
			countyListing: function(){
				var counties = {};
				_.forOwn(this.items, (item) => {
					counties[item.county] = {}
					_.forOwn(item.facilities, (facility) => {
						if (typeof counties[item.county][facility.SUB_COUNTY] == "undefined") {
							counties[item.county][facility.SUB_COUNTY] = [];
						}

						counties[item.county][facility.SUB_COUNTY].push(facility);
					})
				})
				// console.log(counties)
				return counties
			}
		}
	}
</script>