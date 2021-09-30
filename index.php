<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue Practice</title>
    <script src="libraries/Bootstrap/bootstrap.bundle.min.js"></script>
    <script src="libraries/Vue.js/vue.global.prod.js"></script>
    <script src="libraries/axios/axios.min.js"></script>
    <link rel="stylesheet" href="libraries/Bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="app">
        <div id="loading-shade" v-bind:class="{ hidden: !loading }"><div id="loading-hint">ローディング...</div></div>
        <div id="header-area">
            <h1 class="text-center">人員一覽</h1>
            <div class="btn btn-primary float-end me-4">新規作成</div>
        </div>
        <div id="data-display-area" v-if="totalPage > 0" v-cloak>
            <div id="character" class="table container">
                <div class="row thead">
                    <div v-for="column in mainColumns" class="col">{{ column.head }}</div>
                </div>
            </div>
            <!-- <table id="character" class="table">
                <thead>
                    <tr>
                        <th v-for="col in mainColumns">{{ col.head }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(character, cIndex) in characters">
                        <tr>
                            <td v-for="col in mainColumns">{{ character[col.key] }}</td>
                            <td class="toggle-detail" v-on:click="toggleDetail(cIndex)">{{ toggleDetailMark(cIndex) }}</td>
                        </tr>
                        <tr v-bind:class="{ collapse: isDetailShown(cIndex) ? null : true, show: isDetailShown(cIndex) }">
                            <td class="detail-info" v-bind:colspan="mainColumns.length + 1">
                                <div class="table row">
                                    <template v-for="dcol in detailColumns">
                                        <div class="col">{{ dcol.head }}</div>
                                        <div class="col">{{ character[dcol.key] }}</div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table> -->
            <div id="pagination">
                <nav aria-label="page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" v-bind:disabled="isFirstPage">&lt;</a>
                        </li>
                        <li v-for="page in totalPage"
                            v-bind:class="{ 'page-item': true, 'active': isCurrentPage(page).answer }"
                            v-bind:aria-current="isCurrentPage(page).ariaCurrent">
                            <a class="page-link" v-on:click="changePage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" v-bind:disabled="isFinalPage">&gt;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>