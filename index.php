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
        <!-- 「載入中」遮罩 -->
        <div id="loading-shade" v-bind:class="{ hidden: !loading }"><div id="loading-hint">ローディング...</div></div>
        <!-- 頁面頂部 -->
        <div id="header-area">
            <h1 class="text-center">人員一覽</h1>
            <div id="add-character" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-character-modal">新規作成</div>
        </div>
        <!-- 頁面主體 -->
        <div id="data-display-area" v-if="totalPage > 0" v-cloak>
            <!-- 資料展示區 -->
            <div id="character" class="table container-fluid">
                <div class="thead">
                    <div class="row">
                        <div class="col th text-center" v-for="column in mainColumns" v-bind:class="column.class">{{ column.head }}</div>
                        <div class="col th text-center control-buttons"></div>
                        <div class="col th text-center toggle-detail"></div>
                    </div>
                </div>
                <div class="tbody">
                    <template v-for="(character, characterIndex) in characters">
                        <div class="row" v-bind:class="{ expanded: isDetailShown(characterIndex) }">
                            <div class="col td" v-for="column in mainColumns" v-bind:class="column.class">{{ character[column.key] }}</div>
                            <div class="col td control-buttons">
                                <button class="btn btn-info me-1" v-on:click="editCharacter(characterIndex)"
                                        data-bs-toggle="modal" data-bs-target="#edit-character-modal">編集</button>
                                <button class="btn btn-danger" v-on:click="deleteCharacter(characterIndex)"
                                        data-bs-toggle="modal" data-bs-target="#delete-character-modal">削除</button>
                            </div>
                            <div class="col td toggle-detail" v-on:click="toggleDetail(characterIndex)">{{ toggleDetailMark(characterIndex) }}</div>
                        </div>
                        <div class="row detail-info" v-bind:class="{ collapsed: isDetailShown(characterIndex) ? null : true }">
                            <template v-for="detailColumn in detailColumns">
                                <div class="col th">{{ detailColumn.head }}</div>
                                <div class="col td" v-bind:class="detailColumn.class">{{ character[detailColumn.key] }}</div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            <!-- 選頁器 -->
            <div id="pagination">
                <nav aria-label="page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" v-bind:disabled="isFirstPage" v-on:click="goToPreviousPage">&lt;</a>
                        </li>
                        <li v-for="page in totalPage"
                            v-bind:class="{ 'page-item': true, 'active': isCurrentPage(page).answer }"
                            v-bind:aria-current="isCurrentPage(page).ariaCurrent">
                            <a class="page-link" v-on:click="changePage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" v-bind:disabled="isFinalPage" v-on:click="goToNextPage">&gt;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- 模態框：新增 -->
        <div class="modal fade" id="add-character-modal" tabindex="-1" aria-labelledby="add-character-modal-header" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-character-modal-header">新規人員の追加</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col character-no-label">番号</div>
                                <div class="col character-no-input"><input type="text" class="text-center" v-model="newCharacter.No"></div>
                                <div class="col character-id-label">アカウント</div>
                                <div class="col character-id-input"><input type="text" class="text-center" v-model="newCharacter.Id"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-name-label">氏名</div>
                                <div class="col character-name-input"><input type="text" class="text-center" v-model="newCharacter.Name"></div>
                                <div class="col character-gender-label">性別</div>
                                <div class="col character-gender-input"><input type="text" class="text-center" v-model="newCharacter.Gender"></div>
                                <div class="col character-birthday-label">誕生日</div>
                                <div class="col character-birthday-input"><input type="text" class="text-center" v-model="newCharacter.Birthday"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-title-label">職名</div>
                                <div class="col character-title-input"><input type="text" class="text-center" v-model="newCharacter.Title"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-unit-label">所属</div>
                                <div class="col character-unit-input"><input type="text" class="text-center" v-model="newCharacter.Unit"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-email-label">メール</div>
                                <div class="col character-email-input"><input type="text" class="text-center" v-model="newCharacter.Mail"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-mobile-label">携帯電番</div>
                                <div class="col character-mobile-input"><input type="text" class="text-center" v-model="newCharacter.Mobile"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-address-label">出身地</div>
                                <div class="col character-address-input"><input type="text" class="text-center" v-model="newCharacter.Address"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">中止</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確認</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 模態框：修改 -->
        <div class="modal fade" id="edit-character-modal" tabindex="-1" aria-labelledby="edit-character-modal-header" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-character-modal-header">人員データの変更</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col character-no-label">番号</div>
                                <div class="col character-no-input"><input type="text" class="text-center" v-model="currentCharacter.No"></div>
                                <div class="col character-id-label">アカウント</div>
                                <div class="col character-id-input"><input type="text" class="text-center" v-model="currentCharacter.Id"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-name-label">氏名</div>
                                <div class="col character-name-input"><input type="text" class="text-center" v-model="currentCharacter.Name"></div>
                                <div class="col character-gender-label">性別</div>
                                <div class="col character-gender-input"><input type="text" class="text-center" v-model="currentCharacter.Gender"></div>
                                <div class="col character-birthday-label">誕生日</div>
                                <div class="col character-birthday-input"><input type="text" class="text-center" v-model="currentCharacter.Birthday"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-title-label">職名</div>
                                <div class="col character-title-input"><input type="text" class="text-center" v-model="currentCharacter.Title"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-unit-label">所属</div>
                                <div class="col character-unit-input"><input type="text" class="text-center" v-model="currentCharacter.Unit"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-email-label">メール</div>
                                <div class="col character-email-input"><input type="text" class="text-center" v-model="currentCharacter.Mail"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-mobile-label">携帯電番</div>
                                <div class="col character-mobile-input"><input type="text" class="text-center" v-model="currentCharacter.Mobile"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col character-address-label">出身地</div>
                                <div class="col character-address-input"><input type="text" class="text-center" v-model="currentCharacter.Address"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">中止</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確認</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 模態框：刪除 -->
        <div class="modal fade" id="delete-character-modal" tabindex="-1" aria-labelledby="delete-character-modal-header" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete-character-modal-header">人員データの削除</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>本当にこの人員を削除していいのですか？</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">中止</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確認</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>