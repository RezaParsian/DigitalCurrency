<template>
    <div class="relative overflow-x-auto">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>ارزدیجیتال</th>
                <th>قیمت</th>
                <th>قیمت (تومان)</th>
                <th>حجم بازار (میلیارد)</th>
                <th>معاملات روزانه (میلیارد)</th>
                <th>روزانه</th>
                <th>هفتگی</th>
                <th>نمودار هفتگی</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(coin,index) in coins" :key="coin.slug" @click="changePage(coin.id,coin.slug)" class="cursor-pointer">
                <td class="number">
                    {{ index + 1 + ((page - 1) * 50) }}
                </td>

                <td class="number">
                    <div class='flex'>
                        <img :src="`https://cdn.arzdigital.com/uploads/assets/coins/icons/32x32/${coin.slug}.png`" width='20' :alt="coin.name"/>

                        <span class='mr-3'>
                            {{ coin.name }}
                        </span>
                    </div>
                </td>

                <td class="number">
                    {{ selectedFiat.symbol }}{{ coin.price | currency }}
                </td>

                <td class="number">
                    {{ coin.price * selectedFiat.sanaprice |currency | zero }}
                </td>

                <td class="number">
                    ${{ coin.marketcap / 1000000000 | currency4 }}
                </td>

                <td class="number">
                    ${{ coin.volume24h / 1000000000 | currency }}
                </td>

                <td class="text-center">
                <span dir="ltr" :class="{'bg-green-300':coin.d1>0,'bg-rose-400':coin.d1<0}" class="px-2 pt-1 rounded-full number">
                {{ coin.d1 | currency }}
                </span>
                </td>

                <td class="number" dir="ltr" :class="{'text-green-600':coin.d1>0,'text-rose-500':coin.d1<0}">
                    {{ coin.d7 | currency }}
                </td>

                <td class="number">
                    <weekly-chart :coin="coin"/>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>

export default {
    name: "CoinTable",
    props: {
        page: {
            require: true,
            type: Number
        }
    },
    data() {
        return {
            socket: null,
            coins: [],
            fiats: {},
            selectedFiat: null
        }
    },
    methods: {
        connected() {
            this.socketSend('{"action":"fiats","key":"1"}');
            this.socketSend(`{"action":"coins","key":"1","page":"${this.page}"}`);
        },
        socketSend(pm) {
            if (this.socket?.readyState !== WebSocket.OPEN)
                return false;

            this.socket.send(pm);

            return true;
        },
        changePage(id, slug) {
            location = `info/${id}/overview/${slug}`;
        }
    },
    mounted() {
        window.rp=this;

        this.socket = new WebSocket('wss://ws2.arzdigital.com');

        this.socket.onopen = this.connected;

        this.socket.onmessage = (event) => {
            const response = JSON.parse(event.data);

            if (response?.action === "coins")
                this.coins = response.data;

            if (response?.action === "fiats") {
                this.fiats = response.data;

                if (!this.selectedFiat)
                    this.selectedFiat = this.fiats['USD']
            }
        };
    }
}
</script>

<style scoped>

</style>
