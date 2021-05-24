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

                <h3>County Assessment Breakdown</h3>
                <div class="card mb-0" style="padding-top: 15px;">
                    <div class="row" style="text-align: center;">
                        <div class="col-md" v-for="(counties, supervision) in supervisionCounties" :key="supervision">
                            <h5>{{ supervision }}</h5>
                            <h1>{{ counties.length }}</h1>
                        </div>
                    </div>
                </div>
                <hr/>

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

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-header-title">
                                    Classification
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
                    </div>

                    <table class="table table-sm table-bordered table-nowrap card-table">
                        <thead>
                            <th style="width: 37%"></th>
                            <th v-for="(assessment) in pageAssessments" :key="assessment" style="width: 21%;">
                                <center>{{ assessment }}</center>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="(supervisiondata, disease) in pageData" :key="disease">
                                <th style="text-transform: capitalize;">{{ disease }}</th>
                                <td v-for="(assessmentData, assessment) in supervisiondata" :key="assessment">
                                    <center>
                                        <b-link>
                                            {{ calculateClassificationPercentage(disease, assessmentData) }}%
                                        </b-link>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr/>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-header-title">
                                    Treatment
                                </h4>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row" v-for="(supervisiondata, disease) in pageData">
                        <div class="col-md-12">
                            <p style="text-transform: capitalize"><b>{{ disease }}</b></p>
                            <div class="row">
                                <div class="col-md" v-for="(assessmentData, assessment) in supervisiondata">
                                    <disease-area-gauge type="treatment"  :disease-area="disease" :assessment="assessment" :assessment-data="assessmentData"></disease-area-gauge>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <table class="table table-sm table-bordered card-table">
                        <thead>
                            <th style="width: 37%"></th>
                            <th v-for="(assessment) in pageAssessments" :key="assessment" style="width: 21%;">
                                <center>{{ assessment }}</center>
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="(supervisiondata, disease) in pageData" :key="disease">
                                <th style="text-transform: capitalize;">{{ disease }}</th>
                                <td v-for="(assessmentData, assessment) in supervisiondata" :key="assessment">
                                    <center>
                                        <b-link v-b-modal.gaugeModal @click="modal.assessment = assessment; modal.disease = disease;">
                                            {{ calculateTreatmentPercentage(disease, assessment, assessmentData) }}%
                                        </b-link>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <dashboard-modal ref="dashboardModal" :assessment="modal.assessment" :disease="modal.disease" :data="dashboardModalData"></dashboard-modal>
        <!-- <modal ref="chartModal">
            <template v-slot:header>
                <h1>Modal title</h1>
            </template>

            <template v-slot:body>
                <p>Graph comes here</p>
            </template>
        </modal> -->
    </div>
</template>

<script>
import FacilityTypeDonut from "./graphs/snapshot/FacilityTypeDonut"
import DiseaseAreaGauge from "./graphs/snapshot/DiseaseAreaGauge";
// import Modal from "./common/Modal"
import DashboardModal from "./graphs/snapshot/DashboardModal";

export default {
    name: "NewNationalDashboardComponent",
    components: { FacilityTypeDonut, DiseaseAreaGauge, DashboardModal },
    data(){
        return {
            dataLoading: false,
            loaderColor: "#2196F3",
            modal: {
                disease: "",
                assessment: ""
            },
            filters: {
                selectedCounty: ""
            },
            counties: [],
            nationalData: {
                all: {}
            },
            columnColors: {
                'Baseline': '#F7A35C',
                'Facility Supervision 1': '#8085E9',
                'Facility Supervision 2': '#F15C80'
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
        },
        calculateClassificationPercentage(disease, assessmentData){
            if (disease === "pneumonia") {
                return _.round((_.sumBy(assessmentData, 'total_classified')) / (_.sumBy(assessmentData, 'total_cases_after_dif')) * 100)
            }

            return _.round((_.sumBy(assessmentData, 'classified')) / (_.sumBy(assessmentData, 'TOTAL_CASES_AFTER_DIFF')) * 100)
        },
        calculateTreatmentPercentage(disease, assessment, assessmentData){
            let total_recommended_treatment = 0
            let total_treatment = 0
            let gdata = 0

            if (disease === "pneumonia") {
                if (assessment === "Baseline") {
                    total_recommended_treatment = _.sumBy(assessmentData, 'AMOX_SYRUP')
                }else{
                    total_recommended_treatment = _.sumBy(assessmentData, 'AMOXDT')
                }

                let totalsArray = _.map(assessmentData, (data) => {
                   return data.AMOXDT + data.AMOX_SYRUP + data.CTX + data.INJECTABLES + data.NOTX + data.OTHER + data.OXYGEN
                })

                total_treatment = _.sum(totalsArray)
            } else {
                total_recommended_treatment = _.sumBy(assessmentData, 'COP')
                let totalsArray = _.map(assessmentData, (data) => {
                    return data.ANTIBIOTICS + data.COP + data.IV + data.NOTX + data.ORS + data.OTHER + data.ZINC
                })

                total_treatment = _.sum(totalsArray)
            }

            gdata = _.round((total_recommended_treatment / total_treatment) * 100)

            return gdata
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
                        if(data.facility != null) {
                            supervisiondata[supervision].push({
                                id: data.facility.SURVEY_CTO_ID,
                                facility: data.facility.FACILITY_NAME,
                                type: data.facility.FACILITY_TYPE,
                                county_id: data.county.id,
                                county: data.county.county
                            })
                        }else{
                            console.log(data.cname)
                        }
                    })
                })
            })

            _.each(supervisiondata, (data, supervision) => {
                supervisiondata[supervision] = _.uniqBy(data, 'id')
            })

            return supervisiondata
        },
        supervisionCounties: function(){
            let supervisiondata = {};

            _.each( this.pageData, (diseaseData, disease) => {
                _.each(diseaseData, (supervisionData, supervision) => {
                    if( typeof supervisiondata[supervision] === "undefined" ){
                        supervisiondata[supervision] = []

                    }
                    _.each(supervisionData, data => {
                        if(data.county != null) {
                            supervisiondata[supervision].push({
                                id: data.county.id,
                                county: data.county.county
                            })
                        }else{
                            console.log(data.fname)
                        }
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
        dashboardModalData: function(){
            if(this.modal.disease){
                let filteredData = this.pageData[this.modal.disease][this.modal.assessment]
                return filteredData
            }

            return []
        },
        pneumoniaCasesAssessed: function(){
            let pneumoniaData = this.pageData.pneumonia
            let seriesData = []

            _.forOwn(pneumoniaData, (supervisionData, supervision) => {
                let obj = {}
                obj.name = supervision
                obj.data = []
                obj.color = (this.columnColors[supervision])

                let total_cases_after_dif = _.sumBy(supervisionData, 'total_cases_after_dif')

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
                obj.color = (this.columnColors[supervision])

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
        },
        pageAssessments: function(){
            let diarrhoeaData = this.pageData.diarrhoea
            let assessments = [];

            _.forOwn(diarrhoeaData, (supervisionData, supervision) => {
                assessments.push(supervision)
            })

            return assessments
        }
    }
}
</script>

<style lang="scss">
    .overflow-hidden {
        overflow: hidden
    }
</style>
