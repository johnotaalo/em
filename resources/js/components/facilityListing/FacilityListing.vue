<template>
	<div>
		<div class="card">
			<div class="card-header">
				<div class="row align-items-center">
					<div class="col">
						<form class="row align-items-center">
							<div class="col-auto pr-0">
								<span class="fe fe-search text-muted"></span>
							</div>

							<div class="col">
								<b-input type="search" class="form-control form-control-flush search" v-model = "searchTerm" placeholder="Search" v-on:input="applySearchFilter(searchTerm)"/>
							</div>
						</form>
					</div>
					<div class="col-auto">
						<a class="btn btn-sm btn-success" href="#"><i class="fe fe-upload"></i>&nbsp;Upload Facility Listing</a>&nbsp;
						<a class= "btn btn-sm btn-primary float-right" :href="`${baseUrl}/data/facilities/add`"><i class="fe fe-plus-circle"></i>&nbsp;Add Facility</a>
					</div>
				</div>
			</div>
			<div class="card-title">
				<v-server-table
				ref="facilitiesTable"
				class="table-sm table-nowrap card-table"
				:columns="table.columns"
				:options="table.options"
				size="sm">
					<template slot="STATUS" slot-scope="data">
						<span v-if="data.row.STATUS == 1" class="label label-success">Active</span>
						<span v-else class="label label-danger">Inactive</span>
					</template>
				</v-server-table>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import VueTables from 'vue-tables-2'
	const Event = VueTables.Event

	export default{
		data(){
			return {
				searchTerm: "",
				baseUrl: window.Laravel.baseUrl,
				table: {
					columns: [
						'SURVEY_CTO_ID',
						'FACILITY_NAME',
						'COUNTY',
						'SUB_COUNTY',
						'FACILITY_TYPE',
						'STATUS',
						'ACTIONS'
					],
					options: {
						perPage: 50,
						perPageValues: [],
						filterable: false,
						customFilters: ['normalSearch'],
						columnsDropdown: false,
						sortIcon: {
							base: 'icon',
							up: 'icon-sort-up',
							down: 'icon-sort-down',
							is: 'icon-sort'
						},
						pagination: {
							nav: "fixed",
							dropdown: false,
							edge: true,
						},
						requestFunction: (data) => {
							return axios.get(`/api/data/facility-listing`, {
								params: data
							})
							.catch(function (e) {
								console.log('error', e);
							}.bind(this));
						}
					}
				}
			}
		},
		mounted(){
		},
		methods: {
			applySearchFilter: _.debounce(function(e){
				Event.$emit('vue-tables.filter::normalSearch', e);
			}, 500),

		}
	}
</script>