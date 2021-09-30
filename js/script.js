const app = Vue.createApp({
    data() {
        return {
            page: 1,
            limit: 10,
            totalPage: 0,
            characters: [],
            mainColumns: [
                { 'head': '番号',       'key': 'No'     },
                { 'head': 'アカウント', 'key': 'Id'     },
                { 'head': '氏名',       'key': 'Name'   },
                { 'head': '職名',       'key': 'Title'  },
                { 'head': '所属',       'key': 'Unit'   },
                { 'head': 'メール',     'key': 'Email'  },
                { 'head': '携帯電番',   'key': 'Mobile' }
            ],
            detailColumns: [
                { 'head': '性別',   'key': 'Gender'   },
                { 'head': '誕生日', 'key': 'Birthday' },
                { 'head': '出身地', 'key': 'Address'  }
            ],
            loading: null
        };
    },
    methods: {
        getTotalPage() {
            axios.get('api/character/counter')
            .then(response => {
                counter = response.data.Counter;
                this.totalPage = Math.ceil(counter / this.limit);
            });
        },
        getCharacters() {
            this.loading = true;
            axios.get(`api/characters?p=${this.page}&c=${this.limit}`)
            .then(response => {
                response.data.forEach(data => {
                    data.Gender = this.convertGender(Number(data.Gender));
                    data.DetailCollapse = true;
                });
                this.characters = response.data;
                this.loading = null;
            });
        },
        isCurrentPage(page) {
            if (page === this.page) {
                return {
                    answer: true,
                    ariaCurrent: 'page'
                };
            } else {
                return {
                    answer: false,
                    aria: null
                };
            }
        },
        changePage(page) {
            if (page !== this.page) {
                this.page = page;
                console.log(this.page);
                this.getCharacters();
            }
        },
        toggleDetail(index) {
            this.characters[index].DetailCollapse = !this.characters[index].DetailCollapse;
        },
        isDetailShown(index) {
            return this.characters[index].DetailCollapse ? null : true;
        },
        toggleDetailMark(index) {
            return this.isDetailShown(index) ? '▲' : '▼';
        },
        convertGender(gender) {
            return gender === 1 ? '男' : '女';
        }
    },
    computed: {
        isFirstPage() {
            return this.page === 1 ? true : null;
        },
        isFinalPage() {
            return this.page === this.totalPage ? true : null;
        }
    },
    mounted() {
        this.getTotalPage();
        this.getCharacters();
    }
});

const vm = app.mount('#app');
