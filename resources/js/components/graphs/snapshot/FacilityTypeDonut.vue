<template>
    <div class="card">
        <h3 class="text-center mt-2">{{ title }}</h3>
        <highcharts :options="graphOptions" style="height: 220px"></highcharts>
    </div>
</template>

<script>

export default {
    name: "FacilityTypeDonut",
    props: {
        title: {  type: String, default: 'Chart Title' },
        supervisiondata: { type: Array, default: () => { return [] } }
    },
    data(){
        return {
            colors: {
                'DISPENSARY': '#FFD600',
                'HOSPITAL': '#FF6D00',
                'HEALTH CENTRE': '#2962FF',
                'SUB COUNTY HOSPITAL': '#64DD17',
                'OTHER HOSPITAL': '#3E2723',
                'COUNTY HOSPITAL' : '#263238'
            }
        }
    },
    created() {
        console.log(this.title + " component has been created")
    },
    computed: {
        graphOptions: function(){
            let data = []
            let supervisionDistribution = {}
            _.forOwn(this.supervisiondata, (data) => {
                if(typeof supervisionDistribution[data.type] === "undefined"){
                    supervisionDistribution[data.type] = []
                }
                 supervisionDistribution[data.type].push(data)
            })

            _.forOwn(supervisionDistribution, (facilities, facilityType) => {
                var obj = {};
                obj.name = facilityType
                obj.y = facilities.length
                obj.color = this.colors[facilityType]

                data.push(obj)
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
                    enabled: true
                },
                title: null,
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
                    enabled: true,
                    align: 'right',
                    layout: 'vertical',
                    verticalAlign: 'top',
                    floating: false,
                    margin: 100,
                    itemStyle: {
                        fontSize: "10px"
                    }
                },
                plotOptions: {
                    pie: {
                        innerSize: '80%',
                        dataLabels: {
                            enabled: true,
                            formatter: function(){
                                let percentage = (100 * this.y / this.total).toFixed(2) + '%'
                                return `${this.y} (${percentage})`
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: "Facilities",
                    data: data
                }]
            }
        }
    }
}
</script>

<style scoped>

</style>
