<template>
    <b-modal id="gaugeModal" :title="modalTitle">
        <donut-treatment-donut :title="graphTitle" :gdata="graphData" :labels = "treatmentLabels[disease]" :colors="colors[disease]"></donut-treatment-donut>
    </b-modal>
</template>

<script type="text/javascript">
import DonutTreatmentDonut from '../types/DonutTreatmentDonut'
	export default{
		props: {
		    data: { type:Array, default: [] },
            assessment: { type: String, default: "" },
            disease: { type: String, default: "" }
        },
        components: { DonutTreatmentDonut },
        data(){
		    return {
		        title: "",
                treatmentLabels: {
		            pneumonia: {
                        NOTX: "No Treatment",
                        AMOXDT: "Amox DT",
                        AMOX_SYRUP: "Amox Syrup",
                        OXYGEN: "Oxygen",
                        CTX: "CTX",
                        INJECTABLES: "Injectables",
                        OTHER: "Other Treatment",
                    },
                    diarrhoea: {
                        NOTX: "No Treatment",
                        COP: "Co-Pack",
                        ZINC: "Zinc",
                        ORS: "ORS",
                        ANTIBIOTICS: "Antibiotics",
                        IV: "IV Fluids",
                        OTHER: "Other Treatment",
                    }
                },
                colors: {
		            pneumonia: {
                        NOTX: "#FFFFFF",
                        AMOXDT: "#00B0FF",
                        AMOX_SYRUP: "#66BB6A",
                        CTX: "#FF9800",
                        INJECTABLES: "#9E9E9E",
                        OXYGEN: "#7C4DFF",
                        OTHER: "#FFFF00",
                    },
                    diarrhoea: {
                        NOTX: "#FFFFFF",
                        ANTIBIOTICS: "#FFFF00",
                        IV: "#000000",
                        COP: "#03A9F4",
                        ZINC: "#9E9E9E",
                        ORS: "#FF9800",
                        OTHER: "#EF9A9A",
                    }
                }
            }
        },
        mounted(){
		    this.title = `Treatment: ${this.disease} - ${this.assessment}`
        },
        computed: {
		    modalTitle: function() {
		        return `Treatment: ${_.capitalize(this.disease)} - ${this.assessment}`
            },
            graphTitle: function(){
		        return _.capitalize(this.disease)
            },
            graphData: function(){
		        if(this.disease === "pneumonia"){
		            return _.map(this.data, (o) => {
		                return {
                            'AMOXDT' : o.AMOXDT,
                            'AMOX_SYRUP' : o.AMOX_SYRUP,
                            'OXYGEN' : o.OXYGEN,
                            'CTX' : o.CTX,
                            'INJECTABLES' : o.INJECTABLES,
                            'OTHER' : o.ANTI_OTHER,
                            'NOTX' : o.NOTX_DIFF
                        }
                    })
                }else{
		            return _.map(this.data, (o) => {
		                return {
                            NOTX: o.NOTX_CALC,
                            COP: o.COP,
                            ZINC: o.ZINC,
                            ORS: o.ORS,
                            ANTIBIOTICS: o.ANTIBIOTICS,
                            IV: o.IV,
                            OTHER: o.OTHER,
                        }
                    })
                }
            }
        }
	}
</script>
