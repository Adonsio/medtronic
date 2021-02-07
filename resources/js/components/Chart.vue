<template>
    <div id="vuechart">
        <apexchart width="500" type="line" :options="options" :series="series"></apexchart>
        <button @click="updateChart()">Update!</button>
    </div>
</template>

<script>
    import VueApexCharts from 'vue-apexcharts'
    export default {
        name: "Chart",
        components: {
            apexchart: VueApexCharts,
        },
        data: () =>{
            return {
                xdata: [],
                options: {
                    chart: {
                        id: 'vuechart'
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep', 'Nov', 'Dec']
                    }
                },
                series: [{
                    name: 'Supplier 1',
                    data: [10000, 90000, 88000, 142000, 49, 60, 70, 91]
                },
                    {
                        name: 'Supplier 2',
                        data: [20000, 70000, 88000, 132000, 49, 60, 70, 91]
                    }]
            }
        },
        methods: {
            /*
            updateChart(){
                const newData = [];
                for (let i = 0; i < this.xdata.length; i++){
                    newData[i] = this.series[i].data.map(() => {
                        return xdata[i].total_price;
                    })
                this.series[i] = [{
                        name: xdata[i].supplier_name,
                        data: newData[i],
                }]
                }
            },
            */
            updateChart() {
                const max = 90;
                const min = 20;
                for(let i = 0; i < 2; i++){
                const newData = this.series[i].data.map(() => {
                    return Math.floor(Math.random() * (max - min + 1)) + min
                })
                this.series = [{
                    data: newData
                }]
                }
                // In the same way, update the series option


            },
            getX(){
                let app = this;
                axios.get('/api/getX').then(response => app.xdata = response.data.suppliers);
            },
        },
        mounted(){
            this.getX();
        }
    }
</script>

<style scoped>

</style>