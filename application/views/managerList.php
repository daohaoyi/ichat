<body>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
            <span class="navbar-brand font-weight-bold">I-Chat聊天趣</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" id="reportNav" href="<?php echo base_url("Manager/index/report") ?>">檢舉管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="noticetNav" href="<?php echo base_url("Manager/index/notice") ?>">訊息管理</a>
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
        </nav>
        <section class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 p-0 bg-secondary">
                    <div class="accordion" id="accordionExample">

                    </div>
                </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
        </script>
        <script type="text/javascript">
            var BASE_URL = "<?php echo base_url(); ?>";
            var where = "<?php echo $this->uri->segment(3); ?>"
            console.log(where);
        </script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/managerList.js') ?>"></script>
        <!--Javascript、Jquery、Ajax導入/結束-->
    </body>

    </html>