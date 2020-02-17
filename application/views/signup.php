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
                <li class="nav-item active">
                    <a class="nav-link font-weight-bold" href="<?= base_url('Signin/signup') ?>">會員註冊</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="<?= base_url('Signin/manager') ?>">管理員登入</a>
                </li>
            </ul>
        </div>
    </header>


    <section class="container-fluid">
        <div class="row d-flex justify-content-center mt-3">
            <div class="col-10 col-lg-4 p-4 bg-white border border-dark rounded">
      
                <form id="singup" method="POST" enctype="multipart/form-data" accept="image/*">
                    <div class="form-group">
                        <label for="formGroupExampleInput">暱稱</label>
                        <input required autofocus type="text" class="form-control" name="name" placeholder="請輸入不超過12個字元的暱稱" pattern="[\u4e00-\u9fa5_a-zA-Z0-9_]{0,12}" id="formGroupExampleInput" placeholder="Example input">
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">帳號</label>
                        <input required type="text" class="form-control" name="account" placeholder="請輸入6~12個英文OR數字的帳號" pattern="[a-zA-Z0-9]{6,12}" id="formGroupExampleInput2" placeholder="Another input">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密碼</label>
                        <input required type="password" name="password" placeholder="請輸入6~12個英文OR數字的密碼" pattern="[a-zA-Z0-9]{6,12}" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2">確認密碼</label>
                        <input required type="password" name="passconf" placeholder="請確認密碼" pattern="[a-zA-Z0-9]{6,12}" class="form-control" id="exampleInputPassword2" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">信箱</label>
                        <input required type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="請輸入信箱(例:12345@gmail.co,)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="男" checked>
                        <label class="form-check-label" for="inlineRadio1">男性</label>
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="女">
                        <label class="form-check-label" for="inlineRadio2">女性</label>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">檔案上傳</label>
                        <input type="file" name="headStick" accept="image/*" class="form-control-file" id="exampleFormControlFile1">
                    </div>
                    <div class="d-none alert alert-danger" role="alert">
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">註冊</button>
                </form>
            </div>
        </div>
    </section>

    <!--Javascript、Jquery、Ajax導入/結束-->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/signup.js') ?>"></script>
    <!--Javascript、Jquery、Ajax導入/結束-->
</body>

</html>