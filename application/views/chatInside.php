<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <span class="navbar-brand font-weight-bold">I-Chat聊天趣</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url("Chat/list/全部/new") ?>">討論版區</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("Friend/index/list") ?>">我的好友</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("Group/list") ?>">我的群組</a>
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

    <section class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-9 p-0">
                <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top m-0 pr-3">
                    <a class="navbar-brand" href="#">功能列</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav2" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav2">
                        <div class="navbar-nav mr-auto">
                            <?php if ($_SESSION["permission"] == 0) : ?>
                                <button type="button" class="nav-item  btn btn-primary my-2 mr-3" data-toggle="modal" data-target="#addMessageModal">
                                    <i class="fa fa-commenting">
                                        回覆發文
                                    </i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
                <div class="list-group list-group-flush" id="message">

                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addMessageModal" tabindex="-1" role="dialog" aria-labelledby="addMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMessageModalLabel">新增回文</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addChatMessage" method="post">
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
                    <button type="submit" class="btn btn-primary">送出</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">檢舉留言</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addChatReport" method="post">
                    <div class="modal-body" id="report_body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="reasonRadios1" value="1" checked>
                            <label class="form-check-label" for="reasonRadios1">
                                色情或煽情露骨的留言
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reason" id="reasonRadios2" value="2">
                            <label class="form-check-label" for="reasonRadios2">
                                仇恨言論或血腥暴力的內容
                            </label>
                        </div>
                        <div class="d-none alert" role="alert">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">確定</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
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
        var userId = "<?php echo $_SESSION['userId']; ?>";
        var chatId = "<?php echo urldecode($this->uri->segment(3)); ?>";
        console.log(BASE_URL + userId + chatId);
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/chatInside.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>