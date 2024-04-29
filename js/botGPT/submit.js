/** @param {jQuery} $ jQuery Object */
!(function ($, window, document) {
  "use strict";

  XF.BlockSubmit = XF.Click.newHandler({
    eventNameSpace: "BlockSubmit",

    options: {
      delay: 200,
    },

    loading: false,

    init: function () {},

    click: function (e) {
      e.preventDefault();

      if (this.loading) {
        return;
      }
      this.loading = true;

      var t = this;

      var article = $("[name=article]").val();
      var option_bot = $("[name=option_bot]").val();
      var data = {
        article: article,
        option_bot: option_bot,
      };

      var ajaxurl = XF.config.url.fullBase + "bot/gpt-Bot";

      ajaxurl = "http://localhost/xenforo/index.php?bot/gpt-Bot";

      XF.ajax("post", ajaxurl, data, XF.proxy(this, "actionComplete")).always(
        function () {
          setTimeout(function () {
            t.loading = false;
          }, 7500);
        }
      );
    },

    actionComplete: function (data) {

        alert(data.botcontent.content);exit;

      if (data.botcontent.content) {
        $(".overlay-container").removeClass("is-active");

        $(".fr-wrapper .fr-placeholder").css("display", "none");
        $("body").removeClass("is-modalOpen");
        $("body").removeClass("is-modalOverlayOpen");
        $("div.fr-element").empty();
        $(".fr-element").html("<p></p>");
        $(".fr-element p").append(data.botcontent.content);
      }
    },
  });
  XF.Element.register("insert-article", "XF.BlockSubmit");
})(jQuery, window, document);
