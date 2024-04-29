!function ($, window, document, _undefined)
{
    "use strict";

    XF.Reaction = XF.extend(XF.Reaction,
    {
        __backup: {
            'ajaxResponse': '_ajaxResponse'
        },

        actionClick: function(e)
		{
                    
                   
                  
			e.preventDefault();

			if (this.$target.data('tooltip:taphold') && this.$target.is(e.currentTarget))
			{
				// click originated from taphold event
				this.$target.removeData('tooltip:taphold');
				return;
			}

			if (this.loading)
			{
				return;
			}
			this.loading = true;

			var t = this;

                     console.log(e.currentTarget);
			XF.ajax(
				'post',
				$(e.currentTarget).attr('href'),
				XF.proxy(this, 'actionComplete')
			).always(function()
			{
				setTimeout(function()
				{
					t.loading = false;
				}, 250);
			});
		},
                		
                actionComplete: function(data)
		{
                   console.log(data.postid);
                    
			if (!data.html)
			{
				return;
			}

			var $target = this.$target,
				oldReactionId = $target.data('reaction-id'),
				newReactionId = data.reactionId,
				linkReactionId = data.linkReactionId,
                                postAttach=data.appendattach.content,
                                postId=data.postid,
				t = this;

			XF.setupHtmlInsert(data.html, function($html, container, onComplete)
			{
				t.hide();

				var $reaction = $html.find('.js-reaction'),
					$reactionText = $html.find('.js-reactionText'),
					$originalReaction = $target.find('.js-reaction'),
					$originalReactionText = $target.find('.js-reactionText'),
					originalHref = $target.attr('href'), newHref;

				if (linkReactionId)
				{
					newHref = originalHref.replace(/(\?|&)reaction_id=\d+(?=&|$)/, '$1reaction_id=' + linkReactionId);
					$target.attr('href', newHref);
				}

				if (newReactionId)
				{
					$target.addClass('has-reaction');
					$target.removeClass('reaction--imageHidden');
					if (oldReactionId)
					{
						$target.removeClass('reaction--' + oldReactionId);
					}
					$target.addClass('reaction--' + newReactionId);
					$target.data('reaction-id', newReactionId);
                                        $(postAttach).appendTo("#appendattach_"+postId);
				}
				else
				{
					$target.removeClass('has-reaction');
					$target.addClass('reaction--imageHidden');
					if (oldReactionId)
					{
						$target.removeClass('reaction--' + oldReactionId);
						$target.addClass('reaction--' + $html.data('reaction-id'));
						$target.data('reaction-id', 0);
					}
                                        
                                        $("#make_attach_"+postId).remove();
                                        $("#attachpost_"+postId).remove();
                                        
				}

				$originalReaction.replaceWith($reaction);
				if ($originalReactionText && $reactionText)
				{
					$originalReactionText.replaceWith($reactionText);
				}
			});

			var $reactionList = this.options.reactionList ? XF.findRelativeIf(this.options.reactionList, $target) : $([]);

			if (typeof data.reactionList !== 'undefined' && $reactionList.length)
			{
				if (data.reactionList.content)
				{
					XF.setupHtmlInsert(data.reactionList, function($html, container)
					{
						$reactionList.html($html).addClassTransitioned('is-active');
					});
				}
				else
				{
					$reactionList.removeClassTransitioned('is-active', function()
					{
						$reactionList.empty();
					});
				}
			}
		}
    });

    XF.Element.register('reaction', 'XF.Reaction');
}
(jQuery, window, document);