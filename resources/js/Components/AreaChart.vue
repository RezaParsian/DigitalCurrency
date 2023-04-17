<template>
    <div>
        <div class='flex gap-2' dir="ltr">
            <div @click="setRange('24h')" :class='{"!bg-green-400":range==="24h"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                24h
            </div>

            <div @click="setRange('1w')" :class='{"!bg-green-400":range==="1w"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                1w
            </div>

            <div @click="setRange('1m')" :class='{"!bg-green-400":range==="1m"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                1m
            </div>

            <div @click="setRange('3m')" :class='{"!bg-green-400":range==="3m"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                3m
            </div>

            <div @click="setRange('1y')" :class='{"!bg-green-400":range==="1y"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                1y
            </div>

            <div @click="setRange('ALL')" :class='{"!bg-green-400":range==="ALL"}' class="px-4 rounded py-1 bg-zinc-200 hover:bg-red-200 cursor-pointer">
                ALL
            </div>
        </div>

        <apexchart height="400"
                   type="area"
                   :options="options"
                   :series="this.series">
        </apexchart>
    </div>
</template>

<script>
import axios from "axios";

function currency(number, sign = undefined) {
    return new Intl.NumberFormat('en-US', {maximumSignificantDigits: sign}).format(number);
}

export default {
    name: "AreaChart",
    props: {
        id: {
            require: true,
            type: Number
        }
    },
    data() {
        return {
            series: [],
            options: {
                chart: {
                    id: 'area-datetime',
                    zoom: {
                        autoScaleYaxis: true
                    },
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                dataLabels: {
                    enabled: false,
                },
                markers: {
                    size: 0,
                    style: 'hollow',
                },
                yaxis: {
                    labels: {
                        show: false,
                        formatter: function (val) {
                            return currency(val);
                        }
                    },
                },
                xaxis: {
                    type: 'datetime',
                    tickAmount: 6,
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
            },
            range: '1w'
        }
    },
    methods: {
        setRange(range) {
            this.range = range;
            this.getData()
        },
        getData() {
            axios.post('/api/coin/history', {
                coin_id: this.id,
                range: this.range,
            }).then(response => {
                    const series = [];
                    let max = 0;
                    let min = 0;

                    if (response.data?.price) {
                        max = Math.max(...response.data?.price?.map(item => item?.[1]));
                        min = Math.min(...response.data?.price?.map(item => item?.[1]));
                    }

                    Object.entries(response.data).map(([key, value]) => {
                        series.push({
                            data: value,
                            name: key.capitalize()
                        });
                    });

                    this.series = series;

                    this.options['annotations'] = {
                        yaxis: [
                            {
                                y: max,
                                borderColor: '#16A34A',
                                label: {
                                    borderColor: '#16A34A',
                                    style: {
                                        color: '#fff',
                                        background: '#16A34A',
                                    },
                                    text: 'Maximum Price: $' + currency(max),
                                }
                            },
                            {
                                y: min,
                                borderColor: '#e30044',
                                label: {
                                    borderColor: '#e30044',
                                    style: {
                                        color: '#fff',
                                        background: '#e30044',
                                    },
                                    text: 'Minimum Price: $' + currency(min),
                                }
                            }
                        ],
                    }
                }
            )
        }
    },
    mounted() {
        this.getData();
    }
}
</script>

<style scoped>

</style>
