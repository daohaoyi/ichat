$(document).ready(function() {
  get_Message_Ajax();
  setInterval(function() {
    get_Message_Ajax();
    $("html,body").animate({
      //把畫面置底
      scrollTop: $(window).height() + 9999
    });
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
  $.ajax({
    url: BASE_URL + "Group/getMessage",
    method: "POST",
    data: {
      groupId: groupId
    },
    cache: false,
    success: function(e) {
      if (e != "") {
        current = e;
        if (previous !== current) {
          result = current.replace(previous);
          $("#groupMessage").append(result.replace("undefined", ""));
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
    url: BASE_URL + "Group/readMessage",
    type: "POST",
    data: {
      groupId: groupId
    },
    dataType: "text",
    success: function(e) {
      if (e != "") {
        current2 = e;
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        if (previous2 !== current2) {
          for (var i = 0; i < DataLength; i++) {
            if(data[i]["seen"]!=0){
              $("#grmeId" + data[i]["grmeId"]).empty("");
              $("#grmeId" + data[i]["grmeId"]).append("已讀" + data[i]["seen"]);
            }
          }
          console.log(e);
        }
        previous2 = current2;
      }
    },
    error: function() {
      console.log("讀取失敗");
    }
  });
}
function add_Message_Ajax() {
  $("textarea").keypress(function(e) {
    if (e.keyCode == 10 || e.keyCode == 13) {
      //好友訊息表單ajax提交
      event.preventDefault();
      $.ajax({
        url: BASE_URL + "Group/addMessage",
        type: "POST",
        data: $("#sendGroupMessage").serialize(),
        dataType: "text",
        success: function(e) {
          if (e) {
            $("#sendGroupMessage")[0].reset();
            console.log(e);
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
function upload_File_Ajax() {
  $("input[type=file]").change(function() {
    if (!this.files || !this.files[0]) {
      return;
    }
    var filetype = this.files[0].type;
    var formData = new FormData();
    formData.append("file", this.files[0]);
    formData.append("groupId", groupId);
    if (filetype.indexOf("image") > -1) {
      $.ajax({
        url: BASE_URL + "Group/uploadImage",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          console.log(e);
          get_Message_Ajax();
        },
        error: function(e) {
          console.log(e.description);
        }
      });
    }
    if (filetype.indexOf("video") > -1) {
      $.ajax({
        url: BASE_URL + "Group/uploadVideo",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(e) {
          console.log(e);
          get_Message_Ajax();
        },
        error: function(e) {
          console.log(e.description);
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
