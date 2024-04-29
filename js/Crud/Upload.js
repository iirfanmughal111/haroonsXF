function _(el) {
  return document.getElementById(el);
}

function uploadFile() {
  var file = _("bunny_video").files[0];

  var formdata = new FormData();

  formdata.append("bunny_video", file);

  var phraseUrl = XF.canonicalizeUrl("index.php?crud/upload");

  formdata.append("_xfToken", XF.config.csrf);

  $("#overlay").css("display", "block");

  $.ajax({
    type: "POST",
    url: phraseUrl,
    data: formdata,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      $("#response").html(response);
      $("#overlay").css("display", "none");
    },
  });
}
