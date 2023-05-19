<template>
    <div>
        <form class="text-center">
            <textarea placeholder="Введите список доменов" required="required" v-model="domains" class="form-control"
                      name="domains"></textarea>
            <input @click="send()" class="btn mt-3 mx-auto" type="button" value="Отправить">
            <input @click="clear()" class="btn mt-3 mx-auto" type="button" value="Очистить">
        </form>
        <p v-if="alert">{{ alert }}</p>
        <p v-if="loading">Загрузка...</p>
        <div v-for="result in results">
            <p><strong>{{ result.domain }}</strong></p>
            <p v-if="result.available">Статус: {{ result.available }}</p>
            <p v-if="result.created">Дата регистрации: {{ result.created }}</p>
            <p v-if="result.expires">Дата окончания: {{ result.expires }}</p>
            <p v-if="result.owner">Владелец: {{ result.owner }}</p>
            <hr/>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            results: [],
            domains: [],
            alert: '',
            loading: false,
        }
    },
    methods: {
        send() {
            this.results = [];
            this.alert = '';
            const regex = new RegExp(`^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\\.[a-zA-Z]{2,}$`);

            let domainList = this.domains.toLowerCase().replace('\n', ' ').split(' ');
            domainList = Array.from(new Set(domainList));

            let newDomainList = [];
            for (let i = 0; i < domainList.length && regex.test(domainList[i]); i++) {
                newDomainList.push(domainList[i]);
            }

            if (newDomainList.length === 0) {
                this.alert = 'Из списка не найдены домены';
                return;
            }

            for (let i = 0; i < newDomainList.length && regex.test(newDomainList[i]); i++) {
                this.getInfo(newDomainList[i]);
            }
            this.loading = true;

        },
        clear() {
          this.domains = '';
        },
        async getInfo(name) {
            await axios.post('/api/check', {'name': name}).then(response => {
                this.results.push(response.data);
                this.loading = false;
            }).catch(error => {
                this.alert = `Домен: ${name}. Ошибка при выполнения запроса`;
                console.log(error);
                this.loading = false;
            });
        },
    }
}
</script>
