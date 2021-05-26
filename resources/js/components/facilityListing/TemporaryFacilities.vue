<template>
    <div>
        <loading :active.sync="dataLoading" :color="loaderColor" :can-cancel="false"
                 :is-full-page="true"></loading>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">

                    </div>
                    <div class="col-auto">
                        <span class="text-danger" v-if="duplicates">{{ duplicates }} / {{ data.length }} Duplicates in this entry <b-link @click="removeAllDuplicates">Remove All Duplicates</b-link></span>
                        &nbsp;
                        <b-button variant="danger" size="sm" @click="emptyListing">Empty Temporary Listing</b-button>
                        &nbsp;
                        <b-button variant="success" size="sm" @click="confirmTemporaryListing">Push listing to Facility Listing</b-button>
                    </div>
                </div>
            </div>
            <v-client-table
            :columns="table.columns"
            class="table-sm card-table"
            v-model="data"
            :options="table.options">
                <template slot="DUPLICATE" slot-scope="data">
                    <span v-if="data.row.duplicate" class="text-danger">
                        <span class="text-danger">●</span> Duplicate
                    </span>
                    <span v-else class="text-success">
                        <span class="text-success">●</span> New Entry
                    </span>
                </template>

                <template slot="ACTIONS" slot-scope="data">
                    <b-button variant="danger" size="sm" @click="removeFacility(data.row.id)">Remove from List</b-button>
                </template>
            </v-client-table>
        </div>
    </div>
</template>

<script>
export default {
    name: "TemporaryFacilities",
    data(){
        return {
            dataLoading: false,
            loaderColor: "#2196F3",
            data: [],
            table: {
                columns: ['id', 'COUNTY_ID', 'COUNTY', 'SUB_COUNTY_ID', 'SUB_COUNTY', 'FACILITY_NAME', 'FACILITY_TYPE', 'DUPLICATE', 'ACTIONS'],
                options: {
                    filterable: false,
                    perPage: 1000,
                    perPageValues: [],
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
                }
            }
        }
    },
    mounted(){
        this.getData()
    },
    methods: {
        getData: function(){
            this.dataLoading = true
            axios.get('/api/data/temporary-facilities')
            .then(res => {
                this.dataLoading = false
                this.data = res.data
            })
            .catch(error => {
                this.dataLoading = false
                alert(error.message)
            })
        },
        removeAllDuplicates: function(){
            let duplicates = _.filter(this.data, function (facility) {
                if (facility.duplicate)
                    return facility
            })

            this.$swal({
                title: "Are you sure?",
                text: "This shall remove all duplicates from the temporary list",
                icon: "warning",
                buttons: true,
                dangerMode: true
            })
            .then((willDelete) => {
                if (willDelete){
                    this.dataLoading = true
                    axios.post(`/api/data/remove-duplicate-temporary-listing`, {duplicates: duplicates})
                    .then(res => {
                        this.data = _.filter(this.data, (facility) => {
                            if (!facility.duplicate){
                                return facility
                            }
                        })

                        this.$swal('Success', "Successfully removed all duplicates", "success")
                        .then(() => {
                            if (this.data.length === 0){
                                window.location = "/data/facilities"
                            }
                        })
                        this.dataLoading = false
                    })
                    .catch(error => {
                        this.$swal('Error', error.message, 'error')
                        this.dataLoading = false
                    })
                }
            })
        },
        removeFacility: function(id){
            this.$swal({
                title: "Are you sure?",
                text: "Once deleted, this cannot be undone.",
                icon: "warning",
                buttons: true,
                dangerMode: true
            })
            .then((willDelete) => {
                if(willDelete){
                //    Proceed to delete
                    this.dataLoading = true
                    axios.delete(`/api/data/temporary-facilities/${id}`)
                    .then(res => {
                        let index = _.findIndex(this.data, {id: id})
                        this.data.splice(index, 1)
                        this.$swal('Success', 'Successfully removed item from temporary listing', 'success')
                        this.dataLoading = false
                    })
                    .catch(error => {
                        console.log(error.message)
                        this.$swal('Error', "Could not remove item from listing", 'error')
                        this.dataLoading = false
                    })
                }
            })
        },
        confirmTemporaryListing: function(){
            this.$swal({
                title: "Are you sure?",
                text: "This will add all the facilities to the master facilities listing, including duplicates",
                icon: "warning",
                buttons: true,
                dangerMode: true
            })
            .then((willAdd) => {
                if(willAdd){
                    this.dataLoading = true
                    axios.post('/api/data/confirm-temporary-listing', {facilities: this.data})
                    .then(res => {
                        this.dataLoading = false
                        this.$swal('Success', 'Successfully imported all facilities to master facility listing', 'success')
                        .then(() => {
                            window.location = "/data/facilities"
                        })
                    })
                    .catch(error => {
                        this.dataLoading = false
                        this.$swal('Error', error.message, 'error')
                    })
                }
            })
        },
        emptyListing: function(){
            this.$swal({
                title: "Are you sure?",
                text: "This will remove all facilities from the temporary listing",
                icon: "warning",
                buttons: true,
                dangerMode: true
            })
            .then((willDelete) => {
                if (willDelete){
                    this.dataLoading = true
                    axios.post('/api/data/empty-temporary-facility-listing', {data: this.data})
                    .then(res => {
                        this.dataLoading = false
                        this.$swal('Success', "Successfully emptied temporary facility listing", 'success')
                        .then(()=>{
                            window.location = "/data/facilities"
                        })
                    })
                    .catch(error => {
                        this.$swal('Error', error.message, 'error')
                    })
                }
            })
        }
    },
    computed: {
        duplicates: function(){
            return _.filter(this.data, function (facility) {
                if (facility.duplicate)
                    return facility
            }).length
        }
    }
}
</script>

<style scoped>

</style>
