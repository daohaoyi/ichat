<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <span class="navbar-brand font-weight-bold">I-Chat聊天趣</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url("chat/list/全部/new") ?>">討論版區</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("Friend/index/list") ?>">我的好友</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("Group/index/list") ?>">我的群組</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="  " data-toggle="modal" data-target="#noticeModal">聯絡我們</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <img src="<?php echo base_url('assets/images/') . $_SESSION["imgUrl"]; ?>" class="rounded-circle mr-2" alt="<?php echo $_SESSION["imgUrl"] ?>" width="40px" height="40px">
                <button type="button" class="btn btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION["userName"]; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" type="button" id="logout">登出</button>
                </div>
            </ul>
        </div>
    </header>

    <!-- 聯絡我們視窗-->
    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="Notice" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeModalLabel">連絡管理員</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addNotice" method="post">
                        <div class="form-group">
                            <label for="noticeTitle">標題:</label>
                            <input required type="text" placeholder="標題長度限制64字元" class="form-control" name="noticeTitle" id="noticeTitle" whereholder="請輸入標題。">
                        </div>
                        <div class="form-group">
                            <label for="noticeMessage">內容:</label>
                            <input required type="text" placeholder="內容長度限制128字元" class="form-control" name="noticeMessage" id="noticeMessage" whereholder="請輸入內容。">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">創建</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- 聯絡我們視窗 -->

    <section class="container-fluid">
        <div class="row d-flex justify-content-center" style="opacity:0.89">
            <div class="col-lg-9 p-0 bg-secondary">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="dropdown">
                        <a class="navbar-brand dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $sort ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/全部/new"); ?>">全部</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/遊戲/new"); ?>">遊戲</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/美食/new"); ?>">美食</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/電影/new"); ?>">電影</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/心情/new"); ?>">心情</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/3C/new"); ?>">3C</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/汽機/new"); ?>">汽機</a>
                            <a class="dropdown-item" href="<?php echo base_url("chat/list/動漫/new"); ?>">動漫</a>
                        </div>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a id="hot" class="nav-link" href="<?php echo base_url("chat/list/") . urldecode($this->uri->segment(3)) . "/hot"; ?>">熱門</a>
                            </li>
                            <li class="nav-item">
                                <a id="new" class="nav-link" href="<?php echo base_url("chat/list/") . urldecode($this->uri->segment(3)) . "/new"; ?>">最新</a>
                            </li>
                        </ul>
                        <form id="search" class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2" id="searchValue" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            <button type="button" class="btn btn-outline-success ml-2 my-2 my-sm-0" data-toggle="modal" data-target="#addChatModal">
                                創建討論版
                            </button>
                        </form>
                    </div>
                </nav>
                <div class="list-group list-group-flush" id="chatList">
                </div>
                <div id="chatListEnd">
                </div>
            </div>
        </div>
    </section>
    <!-- 創建聊天(彈跳)/開始 -->
    <div class="modal fade" id="addChatModal" tabindex="-1" role="dialog" aria-labelledby="addChatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addChatModalLabel">創建聊天室</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addChat" method="post">
                        <div class="form-group">
                            <label for="chatSort">發文分類:</label>
                            <select class="form-control" name="sort" id="chatSort">
                                <option value="電影">電影</option>
                                <option value="美食">美食</option>
                                <option value="心情">心情</option>
                                <option value="3C">3c</option>
                                <option value="汽機">汽機</option>
                                <option value="遊戲">遊戲</option>
                                <option value="遊戲">動漫</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="chatName">標題:</label>
                            <input required type="text" placeholder="標題長度限制64字元" class="form-control" name="chatName" id="chatName" whereholder="請輸入標題。">
                        </div>
                        <div class="input-group pb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text btn btn-info">
                                    <input id="upload_img" style="display:none;" type="file">
                                    <i class="fa fa-photo"></i>
                                </label>
                            </div>
                            <div id="chatMessage" contenteditable="true" class="form-control h-auto"></div>
                        </div>
                        <div class="d-none alert" role="alert">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">創建</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- 創建聊天(彈跳)/結束 -->
    <!--Javascript、Jquery、Ajax導入/結束-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
        var sort = "<?php echo urldecode($this->uri->segment(3)); ?>";
        var motion = "<?php echo urldecode($this->uri->segment(4)); ?>";
        var find = "<?php echo urldecode($this->input->get("find", TRUE)); ?>";
        var start = 0;
        var limit = 11;
        var action = "inactive";
        console.log(sort + motion + find);
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/chatList.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>