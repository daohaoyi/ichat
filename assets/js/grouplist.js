$(document).ready(function() {
  if (where == "list") {
    get_Group_Ajax();
    get_InviteList_Ajax();
    add_Group_Ajax();
  } else if (where == "review") {
    get_Review_Ajax();
  } else if (where == "record") {
    get_Record_Ajax();
    setInterval(function() {
      get_Record_Ajax();
    }, 10000);
  }
  add_notice();
  logout_ajax();
  css();
});

function css() {
  $("input").mouseup(function() {
    $(".alert").html("");
    $(".alert").addClass("d-none");
    $(".alert").removeClass("alert-danger");
  });
  $("#groupSticker").change(function() {
    readURL(this);
  });
  if (where == "list") {
    $("#list").addClass("active");
  } else if (where == "review") {
    $("#review").addClass("active");
  } else if (where == "record") {
    $("#record").addClass("active");
  }
}
function get_Group_Ajax() {
  //取的群組列表
  $.ajax({
    url: BASE_URL + "Group/getGroup",
    type: "POST",
    dataType: "text",
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#grouptList").append(
            '<button type="button" class="list-group-item list-group-item-action" data-toggle="modal"' +
              'data-target="#groupMenu' +
              data[i]["groupId"] +
              '">' +
              '<div class="d-flex w-100 justify-content-start align-items-center">' +
              '<img src="' +
              BASE_URL +
              "assets/images/" +
              data[i]["imgUrl"] +
              '" class="rounded-circle" alt="' +
              data[i]["groupName"] +
              '" width="40px" height="40px"></img>' +
              '<h5 class="mb-1 p-3 align-items-center flex-grow-1">' +
              data[i]["groupName"] +
              "</h5>" +
              "</div>" +
              "</button>" +
              '<div class="modal fade" id="groupMenu' +
              data[i]["groupId"] +
              '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel' +
              data[i]["groupId"] +
              '"' +
              'aria-hidden="true">' +
              '<div class="modal-dialog" role="document">' +
              '<div class="modal-content">' +
              '<div class="modal-header text-center">' +
              '<h5 class="modal-title" id="exampleModalLabel' +
              data[i]["groupId"] +
              '">' +
              data[i]["groupName"] +
              "</h5>" +
              '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
              '<span aria-hidden="true">&times;</span>' +
              "</button>" +
              "</div>" +
              '<div class="modal-body">' +
              '<a href="' +
              BASE_URL +
              "Group/Inside/" +
              data[i]["groupId"] +
              "/" +
              data[i]["groupName"] +
              '" class="btn btn-success btn-block" >' +
              "聊天" +
              "</a>" +
              '<button type="button" class="btn btn-primary btn-block" data-toggle="modal"' +
              'data-target="#inviteGroupModal' +
              data[i]["groupId"] +
              '" data-dismiss="modal">' +
              "邀請成員" +
              "</button>" +
              '<button type="button" class="btn btn-danger btn-block" data-toggle="modal"' +
              'data-target="#dropOutModal' +
              data[i]["groupId"] +
              '" data-dismiss="modal">' +
              "退出群組" +
              "</button>" +
              "</div>" +
              '<div class="modal-footer">' +
              '<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>' +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              '<div class="modal fade" id="inviteGroupModal' +
              data[i]["groupId"] +
              '"' +
              'tabindex="-1" role="dialog" aria-labelledby="inviteGroupModalLabel" aria-hidden="true" >' +
              '<div class="modal-dialog" role="document">' +
              '<div class="modal-content">' +
              '<div class="modal-header">' +
              '<h5 class="modal-title" id="inviteGroupModalLabel">邀請新成員</h5>' +
              '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
              '<span aria-hidden="true">&times;</span>' +
              "</button>" +
              "</div>" +
              '<form id="inviteMember' +
              data[i]["groupId"] +
              '" method="POST" class="inviteMember">' +
              '<div class="modal-body">' +
              "<label>選擇要邀請的成員</label>" +
              '<div id="menu' +
              data[i]["groupId"] +
              '"></div>' +
              '<div class="d-none alert" role="alert">' +
              "</div>" +
              "</div>" +
              '<div class="modal-footer">' +
              '<button type="submit" class="btn btn-primary">確定</button>' +
              '<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>' +
              "</div>" +
              "</form>" +
              "</div>" +
              "</div>" +
              "</div>" +
              '<div class="modal fade" id="dropOutModal' +
              data[i]["groupId"] +
              '" tabindex="-1" role="dialog"' +
              'aria-labelledby="dropOutModal" aria-hidden="true" >' +
              '<div class="modal-dialog" role="document">' +
              '<div class="modal-content">' +
              '<div class="modal-header">' +
              '<h5 class="modal-title" id="dropOutModal">退出群組</h5>' +
              '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
              '<span aria-hidden="true">&times;</span>' +
              "</button>" +
              "</div>" +
              '<div class="modal-body">' +
              "確定要退出群組?" +
              "</div>" +
              '<div class="modal-footer">' +
              '<button  onClick="dropOut_Group_Ajax(this.id)" type="submit" class="btn btn-primary" id="' +
              data[i]["groupId"] +
              '">確定</button>' +
              '<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>' +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>"
          );
          get_Invite2_Ajax(data[i]["groupName"]);
        }
        add_member_Ajax();
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e);
    }
  });
}
function get_Invite2_Ajax(groupName) {
  $.ajax({
    url: BASE_URL + "Group/getInvite2",
    type: "POST",
    data: {
      groupName
    },
    dataType: "text",
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#menu" + data[i]["groupId"]).append(
            '<input type="hidden" name="groupId" value="' +
              data[i]["groupId"] +
              '">' +
              '<div class="form-group form-check">' +
              '<input class="form-check-input" type="checkbox" id="inlineCheckbox' +
              data[i]["userId"] +
              '" name="inviteMember[]" value="' +
              data[i]["userId"] +
              '">' +
              '<label class="form-check-label" for="inlineCheckbox1">' +
              data[i]["userName"] +
              "</label>" +
              "</div>"
          );
        }
      }
    },
    error: function(e) {
      console.log(e);
    }
  });
}
function add_member_Ajax() {
  $(".inviteMember").submit(function(event) {
    checked = $("input[type=checkbox]:checked").length;
    if (!checked) {
      $(".alert").removeClass("d-none");
      $(".alert").addClass("alert-danger");
      $(".alert").html("您必須至少選中一個複選框");
      return false;
    } else {
      event.preventDefault();
      $.ajax({
        url: BASE_URL + "group/addMember",
        type: "POST",
        data: $(this).serialize(),
        dataType: "text",
        success: function(e) {
          if (e) {
            window.alert("邀請成功");
            window.location.replace(BASE_URL + "Group/index/list");
          }
          console.log(e);
        },
        error: function(e) {
          console.log(e);
        }
      });
    }
  });
}
function add_Group_Ajax() {
  //創建群組
  $("#addGroup").submit(function(event) {
    var form = $("#addGroup")[0];
    var data = new FormData(form);
    checked = $("input[type=checkbox]:checked").length;
    if (!checked) {
      $(".alert").removeClass("d-none");
      $(".alert").addClass("alert-danger");
      $(".alert").html("您必須至少選中一個複選框");
    } else {
      event.preventDefault();
      $.ajax({
        url: BASE_URL + "Group/addGroup",
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          if (e != "") {
            window.alert("群組創建成功");
            window.location.replace(BASE_URL + "Group/index/list");
          } else {
            $(".alert").removeClass("d-none");
            $(".alert").addClass("alert-danger");
            $(".alert").html(e);
          }
        },
        error: function(e) {
          console.log(e);
        }
      });
    }
  });
}
function get_InviteList_Ajax() {
  //取得可邀請好友的列表
  $.ajax({
    url: BASE_URL + "group/getInvite",
    type: "POST",
    dataType: "text",
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#friendList").append(
            '<div class="form-group form-check">' +
              '<input class="form-check-input" type="checkbox" id="inlineCheckbox' +
              (data[i]["inviter"] == userId
                ? data[i]["invitee"]
                : data[i]["inviter"]) +
              '" name="member[]" value="' +
              (data[i]["inviter"] == userId
                ? data[i]["invitee"]
                : data[i]["inviter"]) +
              '">' +
              '<label class="form-check-label" for="inlineCheckbox1">' +
              data[i]["userName"] +
              "</label>" +
              "</div>"
          );
        }
      }
    },
    error: function(e) {
      console.log(e);
    }
  });
}

//好友聊天紀錄的方法
function get_Record_Ajax() {
  $.ajax({
    url: BASE_URL + "Group/getRecord",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        current = e;
        if (previous !== current) {
          result = current.replace(previous);
          $("#grouptList").append(result.replace("undefined", ""));
          $("html,body").animate({
            //把畫面置底
            scrollTop: $(window).height() + 9999
          });
        }
        get_Unread_Ajax();
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
    url: BASE_URL + "Group/getUnread",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#unread" + data[i]["groupId"]).empty("");
          $("#unread" + data[i]["groupId"]).append(data[i]["amount"]);
        }
        console.log(data);
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}

function get_Review_Ajax() {
  $.ajax({
    url: BASE_URL + "group/getReview",
    type: "POST",
    dataType: "text",
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        for (var i = 0; i < DataLength; i++) {
          $("#grouptList").append(
            '<div class="list-group-item list-group-item-action">' +
              '<div class="d-flex justify-content-between">' +
              '<p class="h4 flex-grow-1">' +
              data[i]["groupName"] +
              "</p>" +
              '<button type="submit" id="' +
              data[i]["groupId"] +
              '" onclick="agree_Review_Ajax(this.id)" class="btn btn-primary mr-3">' +
              "同意" +
              "</button>" +
              '<button type="submit" id="' +
              data[i]["groupId"] +
              '" onclick="refuse_Review_Ajax(this.id)" class="btn btn btn-danger">' +
              "拒絕" +
              "</button>" +
              "</form>" +
              "</div>" +
              "</div>"
          );
        }
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e);
    }
  });
}
function agree_Review_Ajax(groupId) {
  $.ajax({
    url: BASE_URL + "group/agreeReview",
    type: "POST",
    data: {
      groupId: groupId
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("同意群組邀請");
        window.location.replace(BASE_URL + "Group/index/list");
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e);
    }
  });
}
function refuse_Review_Ajax(groupId) {
  $.ajax({
    url: BASE_URL + "Group/refuseReview",
    type: "POST",
    data: {
      groupId: groupId
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("拒絕群組邀請");
        window.location.replace(BASE_URL + "Group/index/list");
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e);
    }
  });
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $("#groupSticker_img").attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function dropOut_Group_Ajax(groupId) {
  //退出群組邀請表單ajax提交
  $.ajax({
    url: BASE_URL + "Group/dropOutGroup",
    type: "POST",
    data: {
      groupId: groupId
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.alert("成功退出群組");
        window.location.replace(BASE_URL + "Group/index/list");
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e);
    }
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
