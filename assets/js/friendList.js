$(document).ready(function() {
  css();
  if (where == "list") {
    get_List_AJax();
    setInterval(function() {
      get_List_AJax();
    }, 15000);
  } else if (where == "record") {
    get_Record_Ajax();
    setInterval(function() {
      get_Record_Ajax();
    }, 15000);
  } else if (where == "review") {
    get_Review_Ajxa();
  }
  add_Friend_Ajax();
  add_notice();
  logout_ajax();
});
function css() {
  $("input").mouseup(function() {
    //當滑鼠點擊index當中的input欄位時，警告會消失。
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
  if (where == "list") {
    $("#List").addClass("active");
  } else if (where == "record") {
    $("#Record").addClass("active");
  } else if (where == "review") {
    $("#Review").addClass("active");
  }
}
//好友列表的方法
function get_List_AJax() {
  $.ajax({
    url: BASE_URL + "Friend/getList",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        current = e;
        if (previous !== current) {
          $("#content").empty();
          $("#content").append(e);
        }
        previous = current;
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function add_Friend_Ajax() {
  $("#addFriend").submit(function(event) {
    event.preventDefault();
    $.ajax({
      url: BASE_URL + "Friend/addFriend",
      type: "POST",
      data: $("#addFriend").serialize(),
      dataType: "text",
      success: function(e) {
        if (e.indexOf("成功") != -1) {
          window.alert(e);
          window.location.replace(BASE_URL + "Friend/index/list");
        } else {
          $(".alert").removeClass("d-none");
          $(".alert").addClass("alert-danger");
          $(".alert").html(e);
        }
        console.log(e);
      },
      error: function(e) {
        console.log(e);
      }
    });
  });
}
function delect_Friend_Ajax(friendId) {
  var friendId = friendId;
  $.ajax({
    url: BASE_URL + "friend/delectFriend",
    type: "POST",
    data: {
      friendId: friendId
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("刪除成功");
        window.location.replace(BASE_URL + "Friend/index/list");
      }
    },
    error: function(e) {
      console.log(e);
    }
  });
}
//好友列表的方法

//好友聊天紀錄的方法
function get_Record_Ajax() {
  $.ajax({
    url: BASE_URL + "Friend/getRecord",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        current = e;
        if (previous !== current) {
          $("#content").empty();
          $("#content").append(e);
          get_Unread_Ajax();
        }
        console.log(current);
      }
      previous = current;
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function get_Unread_Ajax() {
  $.ajax({
    url: BASE_URL + "Friend/getUnread",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#unread" + data[i]["fid"]).empty("");
          $("#unread" + data[i]["fid"]).append(data[i]["amount"]);
        }
        console.log(data);
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
//好友聊天紀錄的方法

//好友審核的方法
function get_Review_Ajxa() {
  $.ajax({
    url: BASE_URL + "Friend/getReview",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        $("#content").append(e);
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function agree_Invite_Ajax(friendId) {
  $.ajax({
    url: BASE_URL + "Friend/agreeInvite",
    type: "POST",
    data: { friendId: friendId },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("同意邀請成功");
        window.location.replace(BASE_URL + "Friend/index/review");
      }
    },
    error: function(e) {
      console.log(e);
    }
  });
}
function refuse_Invite_Ajax(friendId) {
  $.ajax({
    url: BASE_URL + "Friend/refuseInvite",
    type: "POST",
    data: { friendId: friendId },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("拒絕邀請成功");
        window.location.replace(BASE_URL + "Friend/index/review");
      }
    },
    error: function(e) {
      console.log(e);
    }
  });
}
//好友審核的方法
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
