import './bootstrap';
import Vue from "vue";

// Vue Components.
import CoinTable from "./Components/CoinTable.vue";
import WeeklyChart from "./Components/WeeklyChart.vue";
import AreaChart from "./Components/AreaChart.vue";
import VueApexCharts from 'vue-apexcharts'

Vue.component('apexchart', VueApexCharts)
Vue.component('CoinTable', CoinTable);
Vue.component('WeeklyChart', WeeklyChart);
Vue.component('AreaChart', AreaChart);

function currency(number, sign = undefined) {
    return new Intl.NumberFormat('en-US', {maximumSignificantDigits: sign}).format(number);
}

function persianNum(input) {
    if (input === undefined || input == null) return "";
    let str1 = input.toString().trim();
    if (str1 === '') return '';
    str1 = str1.replaceAll('0', '۰');
    str1 = str1.replaceAll('1', '۱');
    str1 = str1.replaceAll('2', '۲');
    str1 = str1.replaceAll('3', '۳');
    str1 = str1.replaceAll('4', '۴');
    str1 = str1.replaceAll('5', '۵');
    str1 = str1.replaceAll('6', '۶');
    str1 = str1.replaceAll('7', '۷');
    str1 = str1.replaceAll('8', '۸');
    str1 = str1.replaceAll('9', '۹');
    return str1;
}

String.prototype.capitalize = function () {
    return this.charAt(0).toUpperCase() + this.slice(1)
}

Vue.filter('currency', function (value) {
    return currency(value)
});

Vue.filter('currency4', function (value) {
    return currency(value, 4)
});

Vue.filter('zero', function (value) {
    return value.split('.')[0];
});

const vue = new Vue({
    el: "#app",
})
