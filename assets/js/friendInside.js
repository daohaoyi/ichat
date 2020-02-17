$(document).ready(function() {
  get_Message_Ajax();
  setInterval(function() {
    get_Message_Ajax();
  }, 10000);
  add_Message_Ajax();
  upload_File_Ajax();
  logout_ajax();
  $("html,body").animate({
    //把畫面置底
    scrollTop: $(window).height() + 9999
  });
});
function get_Message_Ajax() {
  //取得該好友編號的聊天訊息
  $.ajax({
    url: BASE_URL + "Friend/getMessage",
    method: "POST",
    data: {
      friendId: friendId
    },
    cache: false,
    success: function(e) {
      if (e != "") {
        current = e;
        if (previous !== current) {
          result = current.replace(previous);
          $("#messageList").append(result.replace("undefined", ""));
          $("html,body").animate({
            //把畫面置底
            scrollTop: $(window).height() + 9999
          });
        }
      }
      read_Message_Ajax();
      previous = current;
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function read_Message_Ajax() {
  //讀取對方訊息
  $.ajax({
    url: BASE_URL + "Friend/readMessage",
    type: "POST",
    data: {
      friendId
    },
    dataType: "text",
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#frmeId" + data[i]["frmeId"]).empty("");
          $("#frmeId" + data[i]["frmeId"]).append("已讀");
        }
        console.log(e);
      }
    },
    error: function() {
      console.log("讀取失敗");
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
    formData.append("friendId", friendId);
    if (filetype.indexOf("image") > -1) {
      $.ajax({
        url: BASE_URL + "Friend/uploadImage",
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
    if (filetype.indexOf("video") > -1) {
      $.ajax({
        url: BASE_URL + "Friend/uploadVideo",
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
function add_Message_Ajax() {
  //傳送好友聊天訊息
  $("textarea").keypress(function(e) {
    if (e.keyCode == 10 || e.keyCode == 13) {
      //好友訊息表單ajax提交
      event.preventDefault();
      $.ajax({
        url: BASE_URL + "Friend/addMessage",
        type: "POST",
        data: $("#sendFriendMessage").serialize(),
        dataType: "text",
        success: function(e) {
          if (e) {
            $("#sendFriendMessage")[0].reset();
            get_Message_Ajax();
            $("html,body").animate({
              //把畫面置底
              scrollTop: $(window).height() + 9999
            });
          }
        },
        error: function(e) {
          console.log(e);
        }
      });
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
