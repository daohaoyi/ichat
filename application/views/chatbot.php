<body class="bg-info">
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <span class="navbar-brand font-weight-bold"><?php echo $name; ?></span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("chat/list/全部/new") ?>">討論版區</a>
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
    <!--header/結束-->
    <section class="container-fluid" style="padding-bottom:94px;" id="messageList">
        <div class="row my-4 d-flex justify-content-center">
            <div class="col-12 col-md-10 d-flex justify-content-start">
                <div class="d-flex justify-content-start">
                    <img src="<?php echo base_url("assets/images/chatbot.png") ?>" class="bg-white rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">
                    <div class="bg-white rounded align-self-center p-2">
                        請輸入問題或是打個招呼獲得更多問題技巧吧！
                    </div>
                    <div class="align-self-end ml-2">
                        <p class="text-left text-nowrap font-weight-bold text-white m-0">
                            <?php
                            date_default_timezone_set("Asia/Taipei");
                            echo  date('H:i:s');
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <footer class="fixed-bottom bg-dark">
        <form id="sendbotMessage" method="POST">
            <div class="input-group py-3 px-3">
                <textarea name="Message" id="Message" class="form-control" aria-label="With textarea"></textarea>
            </div>
        </form>
    </footer>

    <!--Javascript、Jquery、Ajax導入/開始-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
        var friendId = "<?php echo urldecode($this->uri->segment(3)); ?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/chatbot.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>