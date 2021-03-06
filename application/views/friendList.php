<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <span class="navbar-brand font-weight-bold">I-Chat聊天趣</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("Chat/list/全部/new") ?>">討論版區</a>
                </li>
                <li class="nav-item active">
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
        <div class="row d-flex justify-content-center">
            <div class="col-lg-9 p-0 bg-secondary">
                <!--index覽導列/開始-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top m-0 pr-3" style="top: 56px;">
                    <a class="navbar-brand" href="<?php echo base_url("Friend/index/list") ?>">我的好友</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" id="List" href="<?php echo base_url("Friend/index/list") ?>">好友名單</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Record" href="<?php echo base_url("Friend/index/record") ?>">聊天紀錄</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Review" href="<?php echo base_url("Friend/index/review") ?>">好友審核</a>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-outline-success my-2" data-toggle="modal" data-target="#addfriend">
                            添加好友
                        </button>
                    </div>
                </nav>
                <div class="list-group list-group-flush">
                    <?php if (urldecode($this->uri->segment(3) == "list")) : ?>
                        <div id="chatbot">
                            <a href="<?php echo base_url('chatbot/index') ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-start align-items-center">
                                    <img src="<?php echo base_url('assets/images/chatbot.png') ?>" class="rounded-circle mr-3" alt="" width="40px" height="40px">
                                    <h5 class="mb-1 align-items-center flex-grow-1">機器人助理</h5>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div id="content">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addfriend" tabindex="-1" role="dialog" aria-labelledby="addfriendLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addfriendLabel">添加好友</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addFriend" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">請輸入對方的帳號:</label>
                            <input required type="text" class="form-control" name="account" aria-describedby="emailHelp" placeholder="請輸入對方帳號">
                            <small class="form-text text-muted"></small>
                        </div>
                        <div class="d-none alert" role="alert">

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">創建</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Javascript、Jquery、Ajax導入/結束-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
        var where = "<?php echo urldecode($this->uri->segment(3)); ?>";
        var userId = "<?php echo $_SESSION["userId"] ?>";
        var previous = null;
        var current = null;
        console.log(BASE_URL + where + userId);
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/friendList.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>