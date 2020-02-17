$(document).ready(function() {
  sendFriendMessage();
  logout_ajax();
});

function sendFriendMessage() {
  $("textarea").keypress(function(e) {
    if (e.keyCode == 10 || e.keyCode == 13) {
      event.preventDefault();
      var Today = new Date();
      var message = $("#Message").val();
      $("#messageList").append(
        '<div class="clearfix mr-3">' +
          '<div class="media my-3 float-right mw-100">' +
          '<div class="align-self-end text-right mr-2 text-white">' +
          "<span>" +
          "已讀" +
          "</span>" +
          "<br>" +
          "<span>" +
          Today.toLocaleTimeString() +
          "</span>" +
          "</div>" +
          '<div class="media-body border border-dark  bg-white rounded p-2 w-75 align-self-center">' +
          message +
          "</div>" +
          "</div>" +
          "</div>"
      );
      $.ajax({
        url: BASE_URL + "ChatBot/pythonChatBot",
        type: "POST",
        data: $("#sendbotMessage").serialize(),
        dataType: "text",
        success: function(e) {
          $("#messageList").append(
            '<div class="row my-4 d-flex justify-content-center">' +
              '<div class="col-12 col-md-10 d-flex justify-content-start">' +
              '<div class="d-flex justify-content-start">' +
              '<img src="' +
              BASE_URL +
              'assets/images/chatbot.png" class="bg-white rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">' +
              '<div class="bg-white rounded align-self-center p-2">' +
              e +
              "</div>" +
              '<div class="align-self-end ml-2">' +
              '<p class="text-left text-nowrap font-weight-bold text-white m-0">' +
              "</p>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>"
          );
          $("html,body").animate({
            //把畫面置底
            scrollTop: $(window).height() + 9999
          });
        },
        error: function(e) {
          console.log(e);
        }
      });
      $("#sendbotMessage")[0].reset();
    }
    $("html,body").animate({
      scrollTop: $(window).height() + 9999
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
