<template>
    <div>
        <div class='items-center mx-auto text-center' v-if="weekly >= 0">
            <img class='mx-auto text-right justify-center'
                 style="filter: invert(43%) sepia(96%) saturate(1237%) hue-rotate(88deg) brightness(128%) contrast(119%)"
                 :src="`https://cdn.arzdigital.com/uploads/assets/coins/charts/${coin.slug}-chart.svg`"
                 alt="weekly_chart"/>
        </div>

        <div class='items-center mx-auto text-center' v-else>
            <img class='mx-auto text-right justify-center'
                 style="filter: invert(29%) sepia(48%) saturate(7363%) hue-rotate(311deg) brightness(91%) contrast(132.6%)"
                 :src="`https://cdn.arzdigital.com/uploads/assets/coins/charts/${coin.slug}-chart.svg`"
                 alt="weekly_chart"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "WeeklyChart",
    props: {
        coin: {
            require: true,
            type: Object
        }
    },
    computed: {
        weekly() {
            return Math.round((Number(this.coin.d7) + Number.EPSILON) * 100) / 100;
        }
    }
}
</script>

<style scoped>

</style>
