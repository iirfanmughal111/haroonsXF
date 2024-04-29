var Trakt_Movies = window.Trakt_Movies || {};

!function ($, window, document, _undefined)
{
	"use strict";

	Trakt_Movies.Slider = XF.Element.newHandler({
		options: {
			items: null,
			pause: 4000,
			auto: true,
			controls: true,
			loop: false,
			pager: false,
			pauseOnHover: false,
			slideMargin: 10,
			galleryMargin: 5,
			itemsWide: 1,
			breakpointWide: 900,
			itemsMedium: 1,
			breakpointMedium: 480
		},
		breakpoints: {
			2: [700]
		},

		$items: null,
		responsive: [],

		init: function ()
		{
			if (!$.fn.lightSlider)
			{
				console.error('Lightslider must be loaded');
				return;
			}

			this.$items = this.$target.children();

			if (this.options.items === null)
			{
				if (this.$target.attr('class').match(/--show(\d+)/))
				{
					this.options.items = parseInt(RegExp.$1, 10);
				}
				else
				{
					this.options.items = 1;
				}
			}

			this.responsive = this.getResponsiveOptions();

			const effectiveItems = this.getEffectiveItems();
			if (this.$items.length <= effectiveItems)
			{
				return;
			}

			this.slider = this.$target.lightSlider(this.getSliderConfig());

			var self = this;
			$(window).on('resize', function ()
			{
				self.$target.css('height', '');
				self.slider.refresh();
			});
		},

		getResponsiveOptions: function ()
		{
			return [
				{
					breakpoint: this.options.breakpointWide,
					settings: {
						item: this.options.itemsWide
					}
				},
				{
					breakpoint: this.options.breakpointMedium,
					settings: {
						item: this.options.itemsMedium,
					}
				}
			];
		},

		getEffectiveItems: function ()
		{
			let effectiveItems = this.options.items,
				width = $(window).width(),
				bp;

			for (bp in this.responsive)
			{
				if (width < this.responsive[bp].breakpoint)
				{
					effectiveItems = this.responsive[bp].settings.item;
				}
			}

			return effectiveItems;
		},

		getSliderConfig: function ()
		{
			console.log(this.responsive);

			return {
				adaptiveHeight: true,
				item: this.options.items,
				addClass: 'carousel-scrollContainer',
				slideMargin: this.options.slideMargin,
				galleryMargin: this.options.galleryMargin,
				thumbMargin: 5,
				controls: this.options.controls,
				auto: this.options.auto,
				pause: this.options.pause,
				speed: 400,
				loop: this.options.loop,
				pager: this.options.pager,
				pauseOnHover: this.options.pauseOnHover,
				rtl: XF.isRtl(),
				enableDrag: true,
				responsive: this.responsive,
				slideMove: 2,
			};
		}
	});

	XF.Element.register('trakt-movies-slider', 'Trakt_Movies.Slider');
}
(jQuery, window, document);
