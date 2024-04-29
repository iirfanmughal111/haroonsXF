<?php
// FROM HASH: d74524b036e5f45fb2f898477253885c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->inlineJs('
!(function ($, window, document, _undefined) {
  "use strict";

  var socialPostType = "";

  XF.ShowUpgradeBox = XF.Click.newHandler({
    eventNameSpace: "ShowUpgradeBox",

    init: function () {},

    click: function (e) {
      e.preventDefault();

      var $targetUrl = $(e.currentTarget).data("validation-url");

	console.log(this);
      var $premiumId =' . $__vars['xf']['options']['fs_bitcoin_premium_companion_group_id'] . ';
      alert($premiumId);
      //  $targetUrl =
      //    "http://localhost/xenforo/index.php?account-upgrade/purchase";
      XF.ajax("get", $targetUrl, $targetUrl, function (data) {
        if (data.html) {
          $("#purchase_bitcoin").empty();
          $("#purchase_bitcoin").append(data.html.content);
        }
      });
    },
  });

  XF.ClosePostBox = XF.Click.newHandler({
    eventNameSpace: "XFClosePostBox",

    init: function () {},

    click: function (e) {
      e.preventDefault();

      $("#social_post").empty();
      $("#response-error-message").text("");
      $(".post_pop_box").hide();
      $(".import_textbox_url").val(null);
      socialPostType = "";
    },
  });

  XF.Click.register("show-upgrade-box", "XF.ShowUpgradeBox");
  XF.Click.register("close-post-box", "XF.ClosePostBox");
})(jQuery, window, document);
	');
	return $__finalCompiled;
}
);