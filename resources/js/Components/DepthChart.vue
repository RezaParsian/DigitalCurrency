<template>
    <div>
        <apexchart height="400" :options="options" :series="this.series"></apexchart>
    </div>
</template>

<script>

function currency(number, sign = undefined) {
    return new Intl.NumberFormat('en-US', {maximumSignificantDigits: sign}).format(number);
}

export default {
    name: "DepthChart",
    props: {
        bids: {
            require: true,
            type: Array
        },
        asks: {
            require: true,
            type: Array
        }
    },
    data() {
        return {
            options: {
                chart: {
                    type: 'area',
                    height: 350,
                },
                colors: ["red", 'green'],
                markers: {
                    size: 0,
                    style: 'hollow',
                },
                zoom: {
                    enabled: false
                },
                xaxis: {
                    labels: {
                        show: true,
                        formatter: function (val) {
                            return currency(val)
                        }
                    },
                    tickAmount: 6,
                },
                dataLabels: {
                    enabled: false,
                },
                yaxis: {
                    labels: {
                        show: true,
                        formatter: function (val) {
                            return currency(val)
                        }
                    },
                },
            },
        }
    },
    computed: {
        series() {
            return [
                {
                    name: 'Sell',
                    data: this.bids?.map(([item, index]) => {
                        return {
                            x: Number(item),
                            y: Number(index)
                        }
                    })

                },
                {
                    name: 'Buy',
                    data: this.asks?.map(([item, index]) => {
                        return {
                            x: Number(item),
                            y: Number(index)
                        }
                    })
                }

            ];
        }
    }
}
</script>

<style scoped>

</style>
