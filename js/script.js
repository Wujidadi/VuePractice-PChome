const app = Vue.createApp({
    data() {
        return {
            page: 1,
            limit: 10,
            totalPage: 0,
            characters: [],
            mainColumns: [
                { 'head': '番号',       'key': 'No',     'class': 'character-no'     },
                { 'head': 'アカウント', 'key': 'Id',     'class': 'character-id'     },
                { 'head': '氏名',       'key': 'Name',   'class': 'character-name'   },
                { 'head': '職名',       'key': 'Title',  'class': 'character-title'  },
                { 'head': '所属',       'key': 'Unit',   'class': 'character-unit'   },
                { 'head': 'メール',     'key': 'Email',  'class': 'character-email'  },
                { 'head': '携帯電番',   'key': 'Mobile', 'class': 'character-mobile' }
            ],
            detailColumns: [
                { 'head': '性別',   'key': 'AlteredGender',   'class': 'character-gender'   },
                { 'head': '誕生日', 'key': 'AlteredBirthday', 'class': 'character-birthday' },
                { 'head': '出身地', 'key': 'AlteredAddress',  'class': 'character-address'  }
            ],
            loading: null,
            initCharacter: {
                'No': '',
                'Id': '',
                'Name': '',
                'Gender': '1',
                'Birthday': '01/01',
                'Title': '',
                'Unit': '',
                'Email': '',
                'Mobile': '',
                'Address': ''
            },
            newCharacter: {},
            currentCharacter: {}
        };
    },
    methods: {
        characterInitiate() {
            this.newCharacter = JSON.parse(JSON.stringify(this.initCharacter));
            this.currentCharacter = JSON.parse(JSON.stringify(this.initCharacter));
        },
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
                    data.AlteredGender = this.convertGender(Number(data.Gender));
                    data.AlteredBirthday = this.convertBirthday(data.Birthday);
                    data.AlteredAddress = this.convertAddress(data.Address);
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
        },
        convertBirthday(birthday) {
            if (birthday === null || birthday === '') {
                return 'データ無し';
            } else {
                let date = birthday.split('/');
                switch (date.length) {
                    case 3:
                        return `${Number(date[0])} 年 ${Number(date[1])} 月 ${Number(date[2])} 日`;

                    case 2:
                        return `${Number(date[0])} 月 ${Number(date[1])} 日`;

                    default:
                        return birthday;
                }
            }
        },
        convertAddress(address) {
            return (address === null || address === '') ? 'データ無し' : address;
        },
        goToPreviousPage() {
            if (!this.isFirstPage) {
                this.page--;
                this.getCharacters();
            }
        },
        goToNextPage() {
            if (!this.isFinalPage) {
                this.page++;
                this.getCharacters();
            }
        },
        editCharacter(index) {
            this.currentCharacter = JSON.parse(JSON.stringify(this.characters[index]));
        },
        deleteCharacter(index) {
            console.log(this.characters[index].No);
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
    created() {
        this.characterInitiate();
    },
    mounted() {
        this.getTotalPage();
        this.getCharacters();
    }
});

const vm = app.mount('#app');
