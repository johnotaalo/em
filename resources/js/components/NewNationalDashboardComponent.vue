<template>
    <div>
        <loading :active.sync="dataLoading" :color="loaderColor" :can-cancel="false"
                 :is-full-page="true"></loading>
        <div class="row" style="min-height: 90vh">
            <div class="col-md-5" style="border-right: 1px solid #E0E0E0;">
                <b-form-group label="Counties">
                    <b-select size="sm" :options="counties" v-model="filters.selectedCounty"></b-select>
                    <b-link class="text-sm" v-if="filters.selectedCounty" @click="clearSelectedCounty">Show all Counties Data</b-link>
                </b-form-group>

                <b-card>
                    <div class="text-center">
                        <h5>Total Counties</h5>
                        <h1>{{ counties.length }}</h1>
                    </div>
                </b-card>

                <h3>Facilities</h3>
                <div class="card mb-0" style="padding-top: 15px;">
                    <div class="row" style="text-align: center;">
                        <div class="col-md" v-for="(facilities, supervision) in supervisionFacilities" :key="supervision">
                            <h5>{{ supervision }}</h5>
                            <h1>{{ facilities.length }}</h1>
                        </div>
                    </div>
                </div>

                <hr>
                <h3>Facility Types</h3>
                <facility-type-donut v-for="(supervisiondata, supervision) in supervisionFacilities" :key="supervision" :title="supervision" :supervisiondata="supervisiondata"></facility-type-donut>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <highcharts :options="pneumoniaCasesAssessed" style="height: 350px"></highcharts>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <highcharts :options="diarrhoeaCasesAssessed" style="height: 350px"></highcharts>
                        </div>
                    </div>
                </div>

                <h3>Classification</h3>
                <b-card>
                    <div class="row" v-for="(supervisiondata, disease) in pageData" :key="disease">
                        <div class="col-md-12">
                            <p style="text-transform: capitalize"><b>{{ disease }}</b></p>
                            <div class="row">
                                <div class="col-md" v-for="(assessmentData, assessment) in supervisiondata" :key="assessment">
                                    <disease-area-gauge type="classification"  :disease-area="disease" :assessment="assessment" :assessment-data="assessmentData"></disease-area-gauge>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card>
                <hr/>

                <h3>Treatment</h3>
                <b-card>
                    <div class="row" v-for="(supervisiondata, disease) in pageData">
                        <div class="col-md-12">
                            <p style="text-transform: capitalize"><b>{{ disease }}</b></p>
                            <div class="row">
                                <div class="col-md" v-for="(assessmentData, assessment) in supervisiondata">
                                    <disease-area-gauge type="treatment"  :disease-area="disease" :assessment="assessment" :assessment-data="assessmentData"></disease-area-gauge>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import FacilityTypeDonut from "./graphs/snapshot/FacilityTypeDonut"
import DiseaseAreaGauge from "./graphs/snapshot/DiseaseAreaGauge";

export default {
    name: "NewNationalDashboardComponent",
    components: { FacilityTypeDonut, DiseaseAreaGauge },
    data(){
        return {
            dataLoading: false,
            loaderColor: "#2196F3",
            filters: {
                selectedCounty: ""
            },
            counties: [],
            nationalData: {
                all: {}
            }
        }
    },
    created(){
        this.dataLoading = true
        axios.all([this.getComputedData(), this.getCounties()])
        .then(axios.spread((computedDataRes, countiesDataRes)=> {
            this.dataLoading = false
            this.nationalData.all = computedDataRes.data
            this.counties = _.without(_.map(countiesDataRes.data, (county) => {
                if(county.cto_id) {
                    return {
                        value: county.id,
                        text: county.county
                    }
                }
            }), undefined)
        }))
        .catch(error => {
            this.dataLoading = false
            alert(error.message)
        })
    },
    methods: {
        getComputedData: function(){
            return axios.get('/api/data/national')
        },
        getCounties: function(){
            return axios.get('/api/data/counties')
        },
        clearSelectedCounty: function(){
            this.filters.selectedCounty = ""
        }
    },
    computed: {
        supervisionFacilities: function(){
            let supervisiondata = {};

            _.each( this.pageData, (diseaseData, disease) => {
                _.each(diseaseData, (supervisionData, supervision) => {
                    if( typeof supervisiondata[supervision] === "undefined" ){
                        supervisiondata[supervision] = []
                    }
                    _.each(supervisionData, data => {
                        supervisiondata[supervision].push({
                            id: data.facility.SURVEY_CTO_ID,
                            facility: data.facility.FACILITY_NAME,
                            type: data.facility.FACILITY_TYPE,
                            county_id: data.county.id,
                            county: data.county.county
                        })
                    })
                })
            })

            _.each(supervisiondata, (data, supervision) => {
                supervisiondata[supervision] = _.uniqBy(data, 'id')
            })

            return supervisiondata
        },
        pageData: function(){
            let pagedata = {}
            if (!this.filters.selectedCounty){
                return this.nationalData.all
            }else{
                let county_id = this.filters.selectedCounty
                _.forEach(this.nationalData.all, (diseaseAreaData, diseaseArea) => {
                    if(typeof pagedata[diseaseArea] === "undefined"){
                        pagedata[diseaseArea] = {}
                    }
                    _.forEach(diseaseAreaData, (supervisionData, supervision) => {
                        if(typeof pagedata[diseaseArea][supervision] === "undefined"){
                            pagedata[diseaseArea][supervision] = []
                        }

                        let filteredSupervisionData = _.without(_.map(supervisionData, (data) => {
                            // console.log(data)
                            if(data.county.id === county_id)
                                return data
                        }), undefined)

                        pagedata[diseaseArea][supervision] = filteredSupervisionData
                    })
                })

                let cleanedPageData = {}
                _.forEach(pagedata, (diseaseAreaData, diseaseArea) => {
                    if(typeof cleanedPageData[diseaseArea] === "undefined"){
                        cleanedPageData[diseaseArea] = {}
                    }
                    _.forEach(diseaseAreaData, (supervisiondata, supervision) => {
                        if (supervisiondata.length > 0){
                            cleanedPageData[diseaseArea][supervision] = supervisiondata
                        }
                    })
                })
                return cleanedPageData
            }
        },
        pneumoniaCasesAssessed: function(){
            let pneumoniaData = this.pageData.pneumonia
            let seriesData = []

            _.forOwn(pneumoniaData, (supervisionData, supervision) => {
                let obj = {}
                obj.name = supervision
                obj.data = []

                let total_cases_after_dif = _.sumBy(supervisionData, 'total_cases_after_dif');
                // let total_classified = _.sumBy(supervisionData, 'total_classified')

                obj.data.push(total_cases_after_dif)

                seriesData.push(obj)
            })

            return {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Pneumonia Cases Assessed'
                },
                xAxis: {
                    categories: ['Assessments']
                },
                yAxis: {
                    title: {
                        text: 'Cases'
                    }
                },
                series: seriesData,
            }
        },
        diarrhoeaCasesAssessed: function(){
            let diarrhoeaData = this.pageData.diarrhoea
            let seriesData = []

            _.forOwn(diarrhoeaData, (supervisionData, supervision) => {
                let obj = {}
                obj.name = supervision
                obj.data = []

                let total_cases_after_dif = _.sumBy(supervisionData, 'TOTAL_CASES_AFTER_DIFF');
                // let total_classified = _.sumBy(supervisionData, 'total_classified')

                obj.data.push(total_cases_after_dif)

                seriesData.push(obj)
            })

            return {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Diarrhoea Cases Assessed',
                },
                xAxis: {
                    categories: ['Assessments']
                },
                yAxis: {
                    title: {
                        text: 'Cases'
                    }
                },
                series: seriesData,
            }
        }
    }
}
</script>

<style scoped>

</style>
