<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <span class="navbar-brand font-weight-bold">I-Chat聊天趣</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="<?= base_url('Signin') ?>">會員登入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="<?= base_url('Signin/signup') ?>">會員註冊</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link font-weight-bold" href="<?= base_url('Signin/manager') ?>">管理員登入</a>
                </li>
            </ul>
        </div>
    </header>


    <section class="container-fluid">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-8 col-lg-4 p-4 bg-white border border-dark rounded">
                <form id="loginManager" method="POST">
                    <div class="form-group">
                        <label for="exampleInputAccount1">帳號:</label>
                        <input required autofocus type="text" pattern="[a-zA-Z0-9]{6,12}" class="form-control" id="exampleInputAccount1" aria-describedby="accountHelp" placeholder="請輸入帳號" name="account">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密碼:</label>
                        <input required type="password" class="form-control" pattern="[a-zA-Z0-9]{6,12}" id="exampleInputPassword1" placeholder="請輸入密碼" name="password">
                    </div>
                    <div class="d-none alert alert-danger" role="alert">
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">登入</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="fixed-bottom container-fluid bg-dark">
        <p class="text-light text-center font-weight-bold pt-2 pb-2">© Copyright 2018 i-Chat聊天趣</p>
    </footer>

    <!--Javascript、Jquery、Ajax導入/結束-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/manager.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>