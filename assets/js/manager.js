$(document).ready(function() {
  css();
  loginManager_ajax();
});

function css() {
  $("input").mouseup(function() {
    //當滑鼠點擊index當中的input欄位時，警告會消失。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
}

function loginManager_ajax() {
  //登入管理員AJAX
  $("#loginManager").submit(function(event) {
    event.preventDefault();
    $.ajax({
      url: BASE_URL+"Signin/managerLogin",
      type: "POST",
      data: $("#loginManager").serialize(),
      datatype: "text",
      success: function(e) {
        if (e != "") {
          window.alert(e);
          window.location.replace(BASE_URL + "Manager/index/report");
          console.log(e);
        } else {
          $(".alert").removeClass("d-none");
          $(".alert").addClass("alert-danger");
          $(".alert").html("帳號密碼錯誤");
          console.log(e);
        }
      },
      error: function(e) {
        console.log(e.description);
      }
    });
  });
}
