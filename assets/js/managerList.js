$(document).ready(function() {
  if (where == "report") {
    $("#reportNav").addClass("active");
    get_Reprot_Ajax();
  } else {
    $("#noticetNav").addClass("active");
    get_Notice_Ajax();
  }

  logout_ajax();
});

function get_Reprot_Ajax() {
  $.ajax({
    url: BASE_URL + "manager/getReport",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        $("#accordionExample").empty();
        for (var i = 0; i < DataLength; i++) {
          $("#accordionExample").append(
            '<div class="card rounded-0">' +
              '<div class="card-header" id="heading' +
              i +
              '">' +
              '<h2 class="mb-0">' +
              '<button class="btn btn-link" type="button" data-toggle="collapse"' +
              'data-target="#collapse' +
              i +
              '" aria-expanded="true" aria-controls="collapse' +
              i +
              '">' +
              "被檢舉人:" +
              data[i]["userName"] +
              " #" +
              (i + 1) +
              "" +
              "</button>" +
              "</h2>" +
              "</div>" +
              '<div id="collapse' +
              i +
              '" class="collapse" aria-labelledby="heading' +
              i +
              '"' +
              'data-parent="#accordionExample">' +
              '<div class="card-body">' +
              "<p>理由:" +
              (data[i]["reason"] == 1
                ? "色情或煽情露骨的留言"
                : "仇恨言論或血腥暴力的內容") +
              "</p>" +
              "<p>內容:" +
              data[i]["chatMessage"] +
              "</p>" +
              '<div class=" d-flex justify-content-around">' +
              '<button type="button" onClick="yesReport(this.id,' +
              data[i]["reason"] +
              ')" id="' +
              data[i]["chmeId"] +
              '"' +
              'class="btn btn-danger flex-fill mr-3">刪除</button>' +
              '<button type="button" onClick="noReport(this.id)" id="' +
              data[i]["chmeId"] +
              '"' +
              'class="btn btn-success flex-fill">退回</button>' +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>"
          );
        }
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function yesReport(chmeId, reason) {
  $.ajax({
    url: BASE_URL + "manager/yesReport",
    type: "POST",
    data: {
      chmeId: chmeId,
      reason: reason
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.location.replace(BASE_URL + "Manager/index/report");
        window.alert("此訊息確實違規");
      }
      console.log(reason);
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function noReport(chmeId) {
  $.ajax({
    url: BASE_URL + "manager/noReport",
    type: "POST",
    data: {
      chmeId: chmeId
    },
    dataType: "text",
    success: function(e) {
      if (e) {
        window.location.replace(BASE_URL + "Manager/index/report");
        window.alert("此訊息並無違規");
      }
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function get_Notice_Ajax() {
  $.ajax({
    url: BASE_URL + "manager/getNotice",
    method: "POST",
    cache: false,
    success: function(e) {
      if (e != "") {
        var data = jQuery.parseJSON(e);
        var DataLength = data.length; //取出物件長度
        $("#accordionExample").empty();
        for (var i = 0; i < DataLength; i++) {
          $("#accordionExample").append(
            '<div class="card rounded-0">' +
              '<div class="card-header" id="heading' +
              i +
              '">' +
              '<h2 class="mb-0">' +
              '<button class="btn btn-link" type="button" data-toggle="collapse"' +
              'data-target="#collapse' +
              i +
              '" aria-expanded="true" aria-controls="collapse' +
              i +
              '">' +
              "傳訊者:" +
              data[i]["userName"] +
              "</button>" +
              "</h2>" +
              "</div>" +
              '<div id="collapse' +
              i +
              '" class="collapse" aria-labelledby="heading' +
              i +
              '"' +
              'data-parent="#accordionExample">' +
              '<div class="card-body">' +
              "<p>標題:" +
              data[i]["title"]+
              "</p>" +
              "<p>內容:" +
              data[i]["notice"] +
              "</p>" +
              '<div class=" d-flex justify-content-around">' +
              '<button type="button" onClick="delectNotice(this.id)" id="' +
              data[i]["manoId"] +
              '"' +
              'class="btn btn-success flex-fill">觀看完畢</button>' +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>"
          );
        }
      }
      console.log(e);
    },
    error: function(e) {
      console.log(e.description);
    }
  });
}
function delectNotice(manoId) {
  $.ajax({
    url: BASE_URL + "manager/delectNotice",
    type: "POST",
    data: {
      manoId: manoId
    },
    dataType: "text",
    success: function(e) {
        window.location.replace(BASE_URL + "Manager/index/notice");
        window.alert("觀看完畢已刪除");
    },
    error: function(e) {
      console.log(e.description);
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
