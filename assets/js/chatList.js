$(document).ready(function() {
  scroll_load_Chat(); //滾輪載入討論版列表
  search_form(); //搜尋跳轉頁面
  add_Chat_ajax(); //增加文章
  upload_File_Ajax(); //上傳檔案並顯示在前端
  add_notice();
  logout_ajax(); //登出
  css();
});
function scroll_load_Chat() {
  //動態載入;
  if (action == "inactive") {
    action = "active";
    load_get_Chat(start, limit, sort, motion, find);
  }
  $(window).scroll(function() {
    if (
      $(window).scrollTop() + $(window).height() > $("#chatList").height() &&
      action == "inactive"
    ) {
      action = "active";
      start = start + limit;
      setTimeout(function() {
        load_get_Chat(start, limit, sort, motion, find);
      }, 1000);
    }
  });
  //動態載入;
}
function load_get_Chat(start, limit, sort, motion, find) {
  //載入文章
  $.ajax({
    url: BASE_URL + "Chat/getChat",
    method: "POST",
    data: {
      start: start,
      limit: limit,
      sort: sort,
      motion: motion,
      find: find
    },
    cache: false,
    success: function(e) {
      if (e != "") {
        $("#chatList").append(e);
      }
      if ($("#chatList").height() == 0) {
        $("#chatList").append(
          '<a href="#" class="list-group-item list-group-item-action flex-column">' +
            '<div class="d-flex justify-content-start">' +
            "</div>" +
            '<h3 class="mb-1">找不到任何資料...</h3>' +
            "</a>"
        );
        action = "active";
      } else if (e == "" || $("#chatList").height() < 722) {
        $("#chatListEnd").html(
          "<button type='button' class='btn btn-info btn-block'>資料載入完畢。</button>"
        );
        action = "active";
      } else {
        $("#chatListEnd").html(
          "<button type='button' class='btn btn btn-success btn-block'>資料載入中，請稍後....</button>"
        );
        action = "inactive";
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function css() {
  $("input").mouseup(function() {
    //當滑鼠點擊index當中的input欄位時，警告會消失。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
  if (motion == "new") {
    $("#new").addClass("active");
  } else if (motion == "hot") {
    $("#hot").addClass("active");
  } else {
    $("#searchValue").attr("placeholder", find);
  }
}
function upload_File_Ajax() {
  $("input[type=file]").change(function() {
    if (!this.files || !this.files[0]) {
      return;
    }
    var filetype = this.files[0].type;
    var formData = new FormData();
    formData.append("file", this.files[0]);
    if (filetype.indexOf("image") > -1) {
      $.ajax({
        url: BASE_URL + "Chat/uploadImage",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          $("#chatMessage").append(e);
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    }
    if (filetype.indexOf("video") > -1) {
      $.ajax({
        url: BASE_URL + "Chat/uploadVideo",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          $("#chatMessage").append(e);
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    }
  });
}
function add_Chat_ajax() {
  //新增聊天室AJAX
  $("#addChat").submit(function(event) {
    event.preventDefault();
    var chatName = $("#chatName").val();
    var chatSort = $("#chatSort").val();
    var chatMessage = $("#chatMessage").html();
    if (chatMessage !== "") {
      $.ajax({
        url: BASE_URL + "Chat/addChat",
        type: "POST",
        data: {
          chatName: chatName,
          chatSort: chatSort,
          chatMessage: chatMessage
        },
        dataType: "text",
        success: function(e) {
          if (e != "") {
            window.alert(e);
            window.location.replace(BASE_URL + "Chat/list/全部/new");
          } else {
            $(".alert").removeClass("d-none");
            $(".alert").addClass("alert-danger");
            $(".alert").html("名稱重複");
          }
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    } else {
      $(".alert").removeClass("d-none");
      $(".alert").addClass("alert-danger");
      $(".alert").html("內容請不要空白。");
    }
  });
}
function search_form() {
  //搜尋表單
  $("#search").submit(function(event) {
    event.preventDefault();
    window.location.href =
      BASE_URL +
      "Chat/list/" +
      sort +
      "/search" +
      "?find=" +
      $("#searchValue").val();
  });
}
function add_notice(){
  $("#addNotice").submit(function(event) {
    event.preventDefault();
    var noticeTitle = $("#noticeTitle").val();
    var noticeMessage = $("#noticeMessage").val();
      $.ajax({
        url: BASE_URL + "Signin/noticeSend",
        type: "POST",
        data: {
          noticeTitle: noticeTitle,
          noticeMessage: noticeMessage
        },
        dataType: "text",
        success: function(e) {
          window.alert(e);
          $('#noticeModal').modal('hide');
        },
        error: function(e) {
          console.log(e.description);
        }
      });
  });
}
function logout_ajax() {
  $("#logout").click(function() {
    $.ajax({
      url: BASE_URL + "Signin/userLogout",
      type: "POST",
      dataType: "text",
      success: function(e) {
        window.alert(e);
        window.location.replace(BASE_URL + "Signin/index");
      },
      error: function(e) {
        console.log(e.description);
      }
    });
  });
}
