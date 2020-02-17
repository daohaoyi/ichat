$(document).ready(function() {
  css();
  get_Chat_Message_Ajax();
  add_Chat_Message_Ajax();
  upload_File_Ajax();
  add_Chat_Report_Ajax();
  logout_ajax();
});

function css() {
  $("#addChatMessage").mouseup(function() {
    //當滑鼠點擊input欄位，消除警告。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
  $("div[contenteditable]").keydown(function(e) {
    if (e.keyCode === 13 || e.keyCode === 10) {
      document.execCommand("insertHTML", false, "<br><br>");
      return false;
    }
  });
}
function get_Chat_Message_Ajax() {
  //取得該聊天編號的留言訊息
  $.ajax({
    url: BASE_URL + "Chat/getChatMessage",
    method: "POST",
    data: {
      chatId: chatId,
      userId: userId
    },
    cache: false,
    success: function(e) {
      if (e != "") {
        $("#message").append(e);
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function add_Chat_Message_Ajax() {
  $("#addChatMessage").submit(function(event) {
    event.preventDefault();
    var chatMessage = $("#chatMessage").html();
    if (chatMessage !== "") {
      $.ajax({
        url: BASE_URL + "Chat/addChatMeaage",
        type: "POST",
        data: {
          chatMessage: chatMessage,
          chatId: chatId
        },
        dataType: "text",
        success: function(e) {
          if (e) {
            window.alert("回覆成功");
            window.location.replace(BASE_URL + "Chat/inside/" + chatId);
          } else {
            $(".alert").removeClass("d-none");
            $(".alert").addClass("alert-danger");
            $(".alert").html(e);
          }
          console.log(e);
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

function upload_File_Ajax() {
  $("input[type=file]").change(function() {
    if (!this.files || !this.files[0]) {
      return;
    }
    var filetype = this.files[0].type;
    var formData = new FormData();
    formData.append("file", this.files[0]);
    formData.append("chatId", chatId);
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
          console.log(e);
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    }
  });
}

function reportMessage(chmeId) {
  //檢舉聊天編號取得
  $("#chmeId").remove();
  $("#report_body").append(
    '<input type="hidden" id="chmeId" name="chmeId" value="' +
      chmeId +
      '"></input>'
  );
}
function add_Chat_Report_Ajax() {
  //發送檢舉訊息
  $("#addChatReport").submit(function(event) {
    event.preventDefault();
    var reason = $("input[type=radio]:checked").val();
    var chmeId = $("#chmeId").val();
    if (reason !== "") {
      $.ajax({
        url: BASE_URL + "Chat/addChatReport",
        type: "POST",
        data: {
          reason: reason,
          chmeId: chmeId
        },
        dataType: "text",
        success: function(e) {
          if (e) {
            window.alert("檢舉成功");
            window.location.replace(BASE_URL + "Chat/inside/" + chatId);
          } else {
            window.alert("已被檢舉審核中");
            window.location.replace(BASE_URL + "Chat/inside/" + chatId);
          }
          console.log(e);
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    } else {
      $(".alert").removeClass("d-none");
      $(".alert").addClass("alert-danger");
      $(".alert").html("請選擇檢舉的理由。");
    }
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
