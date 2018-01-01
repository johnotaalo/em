<template>
	<div class="row justify-content-center">
		
		<div class="col-12">
			<center><small style="font-size: .7rem"><span v-for="(breakdown, key) in facilityBreakdown" :key="key">{{ breakdown.type | pluralize(breakdown.count) }} {{ breakdown.count }} | </span></small></center>
		</div>
		<div class="col-8 col-lg-10 col-xl-8">
			<loading :active.sync="mapLoading" :color="loaderColor" :can-cancel="false" :is-full-page="false"></loading>
			<highmaps :options="mapData" style="height: 80vh"></highmaps>
		</div>

		<div class="col-4 col-lg-2 col-xl-4">
			<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">

										<h6 class="card-title text-uppercase text-muted mb-2">
											Counties
										</h6>

										<span class="h2 mb-0">
											{{ counties }}
										</span>
									</div>
									<div class="col-auto">

										<span class="h2 fa fa-flag text-muted mb-0"></span>

									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="col">
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">

										<h6 class="card-title text-uppercase text-muted mb-2">
											Facilities
										</h6>

										<span class="h2 mb-0">
											{{ facilities }}
										</span>
									</div>
									<div class="col-auto">

										<span class="h2 fa fa-hospital text-muted mb-0"></span>

									</div>
								</div>

							</div>
						</div>
					</div>
			</div>
			<b-card>
				<highcharts :options="facilityDistributionChart" style="height: 500px;"></highcharts>
			</b-card>
		</div>
	</div>
</template>

<script type="text/javascript">
	import json from '../../../public/counties.json'
	export default {
		data(){
			return {
				mapLoading: false,
				countyData: [],
				facilityBreakdown: {},
				loaderColor: "#2196F3",
				counties: 0,
				facilities: 0
			}
		},

		mounted(){
			axios.get('/api/data/count/facilities')
			.then((response) => {
				var data = response.data;
				this.facilities = data;
			});

			axios.get('/api/data/count/counties')
			.then((response) => {
				var data = response.data;
				this.counties = data;
			});

			axios.get('/api/data/facilities/breakdown')
			.then(res => {
				var breakdown = []
				_.forOwn(res.data, (value, key) => {
					breakdown.push({
						type: _.startCase(_.toLower(key)),
						count: value.length
					});
				})

				this.facilityBreakdown = breakdown
			})

			this.mapLoading = true;
			axios.get('/api/data/county/facilities')
			.then((response) => {
				// console.log(response.data)
				this.mapLoading = false;
				var data = response.data
				this.countyData = _.map(data, (county) => ([ county.cto_id, _.toUpper(county.county), county.facilities.length ]))
			});
		},

		computed: {
			mapData: function(){
				return {
		          chart: {
		            map: json
		          },

		          title: null,
		          mapNavigation: {
		            enabled: false,
		            enableDoubleClickZoomTo: false
		          },

		          colorAxis: {
		            dataClasses: [{
		              from: 1,
		              to: 1000000,
		              color: "#FFC107"
		            }]
		          },

		          legend:{ enabled:false },

		          plotOptions: {
		          	series: {
		          		points: {
		          			events: {
		          				click: function(){
		          					// console.log(this.series);
		          				}
		          			}
		          		}
		          	}
		          },

		          series: [{
		          	showInLegend: false,
		            data: this.countyData,
		            keys: ["cto_id", "COUNTY_NAM", "value"],
		            name: 'County data',
		            joinBy: 'COUNTY_NAM',
		            states: {
		              hover: {
		                color: '#FFE082'
		              }
		            },
		            point: {
		            	events: {
		            		click: function(){
		            			// window.location = `/dashboard/county/breakdown/${this.COUNTY_NAM}`
		            			this.get('Baringo').zoomTo();
		            		}
		            	}
		            },
		            dataLabels: {
		              enabled: false,
		              format: '{point.properties.COUNTY_NAM}'
		            }
		          }],

		          tooltip: {
		            formatter: function(){
		              return _.startCase(_.toLower(this.point.COUNTY_NAM)) + "<br/> Facilities: " + this.point.value
		            }
		          }
		        }
			},
			facilityDistributionChart: function(){
				var facilityCount = 0
				var finalData = _.map(this.facilityBreakdown, (o) => {
					facilityCount += o.count
					return {
						name: o.type,
						y: o.count
					}
				})
				return {
					chart: {
				        type: 'pie',
				        margin: 0,
				        style: {
							fontFamily: 'Nunito'
						},
						options3d: {
							enabled: true,
							alpha: 45
						}
				    },
					credits: {
						enabled: false
					},
				    title: {
				    	verticalAlign: 'middle',
				    	loating: true,
				        text: "<center><b>"+facilityCount+"</b> Facilities<br/>Assessed</center>",
				        style: {
				        	fontSize: "12px"
				        }
				    },
				    xAxis: {
				        categories: ['Facilities'],
				        visible: false
				    },
				    yAxis: {
				        min: 0,
				        gridLineWidth: 0,
				        minorGridLineWidth: 0,
				        visible: false,
				        title: {
				            text: null
				        }
				    },
				    legend: {
				        reversed: true
				    },
				    plotOptions: {
				        pie: {
				        	innerSize: '60%',
				        	dataLabels: {
				        		enabled: true,
				        		format: '<b>{point.name}</b>, {point.y}'
				        	}
				        }
				    },
				    series: [{
				    	name: "Facilities",
				    	data: finalData
				    }]
				}
			}
		}
	}
</script>