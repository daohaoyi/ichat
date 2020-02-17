$(document).ready(function() {
  css();
  singup_ajax();
});
function css() {
  $("input").mouseup(function() {
    //當滑鼠點擊index當中的input欄位時，警告會消失。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
}

function singup_ajax() {
  //註冊會員AJAX
  $("#singup").submit(function(event) {
    event.preventDefault();
    var password = $("#password").val();
    var passconf = $("#passconf").val();
    if (password === passconf) {
      var form = $("#singup")[0];
      var data = new FormData(form);
      $.ajax({
        url: BASE_URL + "Signin/userRegistered",
        type: "POST",
        dataType: "text", //資料格式
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          if (e != "") {
            window.alert(e);
            window.location.replace(BASE_URL);
          } else {
            $(".alert").removeClass("d-none");
            $(".alert").addClass("alert-danger");
            $(".alert").html("此帳號已被註冊");
            console.log(e);
          }
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    } else {
    }
  });
}
