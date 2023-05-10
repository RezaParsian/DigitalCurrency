<template>
    <div class="bg-emerald-700 col-span-3 rounded grid p-4" dir="ltr">

        <select class="w-1/2" v-model="selectedDevice">
            <option v-for="device in devices" :key="device.devid" :value="device">
                {{ device.name }}
            </option>
        </select>

        <div class="bg-white mx-auto my-auto rounded mt-4 grid md:grid-cols-6 text-center">
            <div class="col-span-3 border-slate-300 border-r p-4 border-b-2">
                <p class="my-4">سود خالص ماهانه</p>

                <h2 class="text-4xl text-[#0cb983] font-bold">
                    ${{ currency(profitDollar) }}
                </h2>

                <div dir="rtl" class="text-slate-500">
                    <span>{{ currency(profitToman) }}</span>

                    <span class="mx-1">تومان</span>
                </div>

                <p class="text-right" dir="rtl">محاسبات ماهانه :</p>

                <ul class="text-right" dir="rtl">
                    <li class="grid grid-cols-2 gap-1 m-1">
                        <p class="bg-slate-200 rounded p-1">هزینه برق مصرفی</p>
                        <b class="bg-slate-100 rounded p-1 powerCost">{{ currency(powerCost) }}</b>
                    </li>

                    <li class="grid grid-cols-2 gap-1 m-1">
                        <p class="bg-slate-200 rounded p-1">کارمزد استخر</p>
                        <b class="bg-slate-100 rounded p-1 poolFee">{{ currency(poolFee) }}</b>
                    </li>

                    <li class="grid grid-cols-2 gap-1 m-1">
                        <p class="bg-slate-200 rounded p-1">درآمد اصلی</p>
                        <b class="bg-slate-100 rounded p-1 incomeCoin">{{ currency(incomeCoin) }}</b>
                    </li>

                    <li class="grid grid-cols-2 gap-1 m-1">
                        <p class="bg-slate-200 rounded p-1">درآمد به تومان</p>
                        <b class="bg-slate-100 rounded p-1 incomeToman">{{ currency(incomeToman) }}</b>
                    </li>

                    <li class="grid grid-cols-2 gap-1 m-1">
                        <p class="bg-slate-200 rounded p-1">درآمد به دلار</p>
                        <b class="bg-slate-100 rounded p-1 incomeDollar">{{ currency(incomeDollar) }}</b>
                    </li>

                </ul>

                <small>دلار با نرخ {{ currency(dollarPrice) }} تومان محاسبه شده است</small>
            </div>

            <div class="col-span-3 border-slate-300 border-l border-b-2 p-4">
                <div class="grid grid-cols-2 gap-1">
                    <div>
                        <label>Hash Rate</label>

                        <input type="number" v-model="hashRate"/>
                    </div>

                    <div>
                        <label>نرخ هش</label>

                        <select v-model="hashRateUnit">
                            <option value="h/s">H/s</option>
                            <option value="g/s">G/s</option>
                            <option value="sol/s">Sol/s</option>
                            <option value="kh/s">Kh/s</option>
                            <option value="ksol/s">Ksol/s</option>
                            <option value="mh/s">Mh/s</option>
                            <option value="gh/s">Gh/s</option>
                            <option value="th/s">Th/s</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-1 mt-4">
                    <div>
                        <label class="float-left">Power consumption (w)</label>

                        <label class="float-right">برق مصرفی به وات</label>

                        <input type="number" v-model.number="selectedDevice.power"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-1 mt-4">
                    <div>
                        <label>Cost per KWh</label>

                        <input type="number" v-model="cost"/>
                    </div>

                    <div>
                        <label class="text-xs md:text-base">هزینه هر کیلو وات ساعت برق</label>

                        <select v-model="cost">
                            <option value="90">صنعتی</option>
                            <option value="1930">صادراتی فصل گرم</option>
                            <option value="482">صادراتی سایر ایام</option>
                            <option value="965">متوسط صادراتی</option>
                            <option value="50">حرارتی</option>
                            <option value="220">پراکنده</option>
                            <option value="600">خورشیدی</option>
                            <option value="48">بورس‌انرژی</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-1 mt-4">
                    <div>
                        <label class="float-left">Pool Fee (%)</label>

                        <label class="float-right">کارمزد استخر استخراج</label>

                        <input type="number" v-model="fee"/>
                    </div>
                </div>
            </div>

            <div class="col-span-4 border-slate-300 border-r p-4 grid grid-cols-2 md:grid-cols-3 gap-4 text-right" dir="rtl">
                <div class="grid">
                    <small class="text-slate-400">الگوریتم :</small>

                    <small>{{ coin.consensus }}</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">سختی شبکه :</small>

                    <small>{{ coin.difficulty }}</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">نرخ هش کل شبکه :</small>

                    <small>{{ coin.nethash }}</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">حجم بازار :</small>

                    <small>{{ coin.marketcap }} دلار</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">زمان بلاک :</small>

                    <small>{{ coin.blocktime }} ثانیه</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">پاداش بلاک :</small>

                    <small>{{ coin.blockreward }} واحد</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">درآمد روزانه شبکه :</small>

                    <small>{{ currency(coin.minedPrices.d) }} $</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">درآمد ماهانه شبکه :</small>

                    <small>{{ currency(coin.minedPrices.m) }} $</small>
                </div>

                <div class="grid">
                    <small class="text-slate-400">درآمد سالانه شبکه :</small>

                    <small>{{ currency(coin.minedPrices.y) }} $</small>
                </div>
            </div>

            <div class="border-slate-300 col-span-3 md:col-span-2 border-l p-4 flex">
                <div class="m-auto">
                    <p dir="rtl">قیمت فعلی {{ coinInfo.name }}</p>

                    <h2 class="text-2xl text-[#0cb983] font-bold">${{ currency(coin.price) }}</h2>

                    <p dir="rtl" class="text-slate-500">{{ currency(coin.rial_price) }} تومان</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MiningCalculator",
    props: {
        devices: {
            required: true,
            type: Object
        },
        coinInfo: {
            require: true,
            type: Object
        },
        coin: {
            require: true,
            type: Object
        },
        dollarPrice: {
            require: true,
            type: Number
        }
    },
    data() {
        return {
            selectedDevice: {...this.devices[Object.keys(this.devices)[0]]},
            hashRateUnit: 'th/s',
            cost: 90,
            fee: 1,
            consensus: 0,
            difficulty: 0,
            netHash: 0,
            rialPrice: 0,
            incomeCoin: 0,
            powerCost: 0,
            poolFee: 0,
            incomeDollar: 0,
            incomeToman: 0,
            profitDollar: 0,
            profitToman: 0,
        }
    },
    methods: {
        currency(number, sign = undefined) {
            return new Intl.NumberFormat('en-US', {maximumSignificantDigits: sign}).format(number);
        },
        nFormatter2(number, point = 0) {
            if (!this.coin?.consensus)
                return;

            const n = [
                {
                    value: 1000000000000000000,
                    symbol: 'E'
                },
                {
                    value: 1000000000000000,
                    symbol: 'P'
                },
                {
                    value: 1000000000000,
                    symbol: ' تریلیون '
                },
                {
                    value: 1000000000,
                    symbol: ' میلیارد '
                },
                {
                    value: 1000000,
                    symbol: ' میلیون '
                },
                {
                    value: 1000,
                    symbol: ' هزار '
                }
            ];

            let regExp = /\.0+$|(\.[0-9]*[1-9])0+$/;

            for (let a = 0; a < n.length; a++)
                if (number >= n[a].value) return {
                    n: (number / n[a].value).toFixed(point).replace(regExp, '$1'),
                    s: n[a].symbol
                };

            return number.toFixed(point).replace(regExp, '$1')
        },
        calculate() {
            if (!this.coin?.consensus)
                return;

            let netHashRate = this.coin.nethash;

            let hashrate = this.hashRate;

            switch (this.hashRateUnit) {
                case 'h/s':
                case 'g/s':
                case 'sol/s':
                    hashrate *= 1;
                    break;
                case 'kh/s':
                case 'ksol/s':
                    hashrate *= 1000;
                    break;
                case 'mh/s':
                    hashrate *= 1000000;
                    break;
                case 'gh/s':
                    hashrate *= 1000000000;
                    break;
                case 'th/s':
                    hashrate *= 1000000000000;
                    break
                default:
                    hashrate *= 1;
            }

            let ourReward = (hashrate * this.coin.blockreward) / netHashRate,
                minedPerSecond = ourReward / this.coin.blocktime2,
                seconds = 30 * 24 * 3600,
                mined = minedPerSecond * seconds,
                powerCost = this.selectedDevice.power * seconds / 1000 / 60 / 60 * this.cost,
                minedPrice = this.coin.price * mined,
                feePrice = minedPrice * this.fee / 100,
                profit = minedPrice - (feePrice + (powerCost / this.dollarPrice));

            this.incomeCoin = mined;
            this.incomeDollar = minedPrice;
            this.incomeToman = minedPrice * this.dollarPrice;
            this.powerCost = powerCost;
            this.poolFee = feePrice;
            this.profitDollar = profit;
            this.profitToman = profit * this.dollarPrice;
        },
    },
    computed: {
        hashRate: {
            get() {
                return this.selectedDevice?.algos?.[0]?.number ?? 0;
            },
            set(value) {
                this.selectedDevice.algos[0].number = value;
            }
        }
    },
    watch: {
        hashRate() {
            this.calculate();
        },
        selectedDevice() {
            this.calculate();
        },
        hashRateUnit() {
            this.calculate();
        },
        cost() {
            this.calculate();
        },
        fee() {
            this.calculate();
        },
        consensus() {
            this.calculate();
        },
        difficulty() {
            this.calculate();
        },
        netHash() {
            this.calculate();
        },
        marketCap() {
            this.calculate();
        },
        blockTime() {
            this.calculate();
        },
        blockReward() {
            this.calculate();
        },
        minedPricesD() {
            this.calculate();
        },
        minedPricesM() {
            this.calculate();
        },
        minedPricesY() {
            this.calculate();
        },
        rialPrice() {
            this.calculate();
        },
        price() {
            this.calculate();
        },
    },
    mounted() {
        this.calculate();
    }
}
</script>

<style scoped>

</style>
