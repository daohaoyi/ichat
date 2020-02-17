$(document).ready(function() {
  css();
  login_ajax();
});

function css() {
  $("input").mouseup(function() {
    //當滑鼠點擊index當中的input欄位時，警告會消失。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
}

function login_ajax() { //登入會員AJAX
  $("#login").submit(function(event) {
    //登入表單ajxax
    event.preventDefault();
    $.ajax({
      url: BASE_URL + "Signin/userLogin",
      type: "POST",
      dataType: "text", //資料格式
      data: $("#login").serialize(),
      success: function(e) {
        if (e != "") {
          window.alert(e);
          window.location.replace(BASE_URL + "Chat/list/全部/new");
        } else {
          $(".alert").removeClass("d-none");
          $(".alert").addClass("alert-danger");
          $(".alert").html("帳號密碼錯誤");
          console.log(e);
        }
        console.log(e);
      },
      error: function(e) {
        console.log(e.description);
      }
    });
  });
}
