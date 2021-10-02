const app = Vue.createApp({
    data() {
        return {
            queryParams: {},
            page: 1,
            limit: 10,
            totalPage: 0,
            nextNo: '',
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
            basicPagination: 10,
            loading: null,
            initCharacter: {
                'No': '',
                'Id': '',
                'Name': '',
                'Gender': 1,
                'Birthday': '01/01',
                'Title': '',
                'Unit': '',
                'Email': '',
                'Mobile': '',
                'Address': ''
            },
            newCharacter: {},
            currentCharacter: {},
            oldEditingCharacter: {},
            characterNoToDelete: '',
            bsModal: {
                'addModal': null,
                'editModal': null,
                'deleteModal': null
            }
        };
    },
    methods: {
        getQueryString() {
            if (location.search !== '') {
                let params = location.search.replace(/^\?/, '').split('&').forEach(element => {
                    param = element.split('=');
                    this.queryParams[param[0]] = param[1];
                });
                if (this.queryParams.p !== undefined) {
                    this.page = Number(this.queryParams.p);
                }
                if (this.queryParams.c !== undefined) {
                    this.limit = Number(this.queryParams.c);
                }
            }
        },
        getNextNo() {
            axios.get('api/characters/nextNo')
            .then(response => {
                this.nextNo = response.data.NextNo;
            })
            .then(() => {
                this.newCharacter.No = this.nextNo;
            });
        },
        characterInitiate() {
            this.newCharacter = JSON.parse(JSON.stringify(this.initCharacter));
            this.currentCharacter = JSON.parse(JSON.stringify(this.initCharacter));
            this.getNextNo();
        },
        bsModalInitiate() {
            this.bsModal.addModal = new bootstrap.Modal(document.querySelector('#add-character-modal'), { keyboard: false });
            this.bsModal.editModal = new bootstrap.Modal(document.querySelector('#edit-character-modal'), { keyboard: false });
            this.bsModal.deleteModal = new bootstrap.Modal(document.querySelector('#delete-character-modal'), { keyboard: false });
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
            axios.get(`api/characters/?p=${this.page}&c=${this.limit}`)
            .then(response => {
                response.data.forEach(data => {
                    data.Gender = Number(data.Gender);
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
        pushPageToHistory(page) {
            if (/\?[^?]*p=\d+/.test(location.href)) {
                window.history.pushState({}, 0, location.href.replace(/p=\d+/, `p=${page}`));
            } else {
                let locationBody = location.origin + location.pathname,
                    locationSearch = location.search;
                if (/^\?/.test(locationSearch)) {
                    locationSearch = locationSearch.replace(/^\?(.*)/, `?p=${page}&$1`);
                    window.history.pushState({}, 0, locationBody + locationSearch);
                } else {
                    window.history.pushState({}, 0, `${locationBody}?p=${page}`);
                }
            }
        },
        changePage(page) {
            if (page !== this.page) {
                this.page = page;
                this.pushPageToHistory(this.page);
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
        goToFirstPage() {
            if (!this.isFirstPage) {
                this.page = 1;
                this.pushPageToHistory(this.page);
                this.getCharacters();
            }
        },
        goToPreviousPage() {
            if (!this.isFirstPage) {
                this.pushPageToHistory(--this.page);
                this.getCharacters();
            }
        },
        goToNextPage() {
            if (!this.isFinalPage) {
                this.pushPageToHistory(++this.page);
                this.getCharacters();
            }
        },
        goToFinalPage() {
            if (!this.isFinalPage) {
                this.page = this.totalPage;
                this.pushPageToHistory(this.page);
                this.getCharacters();
            }
        },
        showAddModal() {
            this.bsModal.addModal.show();
        },
        addCharacter() {
            if (this.newCharacter.No == '' || this.newCharacter.Id == '') {
                alert('番号とIDが必要です');
            } else {
                this.newCharacter.Gender = parseInt(this.newCharacter.Gender);
                axios.post('api/character/', this.newCharacter)
                .then(response => {
                    // console.log(response.data);
                    this.bsModal.addModal.hide();
                    this.getTotalPage();
                    this.getCharacters();
                })
                .catch(error => {
                    // console.log(error.response);
                    alert(error.response.data.status);
                });
            }
        },
        showEditModal(index) {
            this.currentCharacter = JSON.parse(JSON.stringify(this.characters[index]));
            this.oldEditingCharacter = JSON.parse(JSON.stringify(this.characters[index]));
            this.bsModal.editModal.show();
        },
        editCharacter(index) {
            let counter = 0,
                newData = {};
            Object.keys(this.currentCharacter).forEach(key => {
                if (this.currentCharacter[key] != this.oldEditingCharacter[key]) {
                    counter++;
                    if (key === 'Gender') {
                        newData[key] = Number(this.currentCharacter[key]);
                    } else {
                        newData[key] = this.currentCharacter[key];
                    }
                }
            });
            if (counter > 0) {
                newData.No = this.currentCharacter.No;
                axios.patch('api/character/', newData)
                .then(response => {
                    this.bsModal.editModal.hide();
                    this.getCharacters();
                })
                .catch(error => {
                    alert(error.response.data.status);
                });
            } else {
                alert('新しいデータは旧いデータと同じ');
            }
        },
        showDeleteModal(index) {
            this.characterNoToDelete = this.characters[index].No;
            this.bsModal.deleteModal.show();
        },
        deleteCharacter() {
            let me = this;
            axios.delete('api/character/', {
                data: {
                    'No': this.characterNoToDelete
                }
            })
            .then(response => {
                this.bsModal.deleteModal.hide();
                this.getCharacters();
                this.getTotalPage();
                if (this.characters.length - 1 === 0 && this.page > 1)
                {
                    this.pushPageToHistory(--this.page);
                    this.getCharacters();
                };
            })
            .catch(error => {
                console.log(error);
                // alert(error.response.data.status);
            });
        },
        cleanNewCharacter() {
            document.querySelector('#add-character-modal')
            .addEventListener('hidden.bs.modal', event => {
                this.newCharacter = JSON.parse(JSON.stringify(this.initCharacter));
                this.getNextNo();
            });
        }
    },
    computed: {
        isFirstPage() {
            return this.page === 1 ? true : null;
        },
        isFinalPage() {
            return this.page === this.totalPage ? true : null;
        },
        pagination()
        {
            let len = 1,
                offset = 0;

            if (this.totalPage <= this.basicPagination)
            {
                len = this.totalPage;
                offset = 1;
            }
            else
            {
                len = this.basicPagination;

                if (this.page > this.basicPagination && this.totalPage - this.page < this.basicPagination)
                {
                    offset = this.totalPage - this.basicPagination + 1;
                }
                else
                {
                    let tempPage = (this.page % this.basicPagination > 0) ? this.page : this.page - 1;
                    offset = Math.floor(tempPage / this.basicPagination) * this.basicPagination + 1;
                }
            }

            let pagination = Array.from({length: len}, (_, i) => i + offset);

            if (pagination[0] > 1)
            {
                pagination.unshift(1);
            }
            if (pagination[pagination.length - 1] < this.totalPage)
            {
                pagination.push(this.totalPage);
            }

            return pagination;
        }
    },
    created() {
        this.getQueryString();
        this.characterInitiate();
    },
    mounted() {
        this.bsModalInitiate();
        this.cleanNewCharacter();
        this.getTotalPage();
        this.getCharacters();
    }
});

const vm = app.mount('#app');
