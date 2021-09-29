const app = Vue.createApp({
    data() {
        return {
            fetch: null
        };
    },
    methods: {
        getFile() {
            axios.get('api/get-list')
            .then(response => {
                console.log(response.data);
            });
        }
    },
    mounted() {
        this.getFile()
    }
});

const vm = app.mount('#app');
