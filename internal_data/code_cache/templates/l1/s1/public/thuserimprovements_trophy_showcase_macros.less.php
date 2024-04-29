<?php
// FROM HASH: b271ef893c56acead2cc42bb8ee8fff0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.trophyShowcase {
	.xf-klUITrophyShowcase();

	.trophyIconItem {
		.xf-klUITrophyIconItem();
		cursor: pointer;
		color: inherit;
		text-decoration: none;
	}

	&.trophyShowcase--link:hover {
		cursor: pointer;
		text-decoration: none;
		.xf-klUITrophyShowcase();
	}

	&.trophyShowcase--postbit {
		.xf-klUITrophyShowcasePostbit();

		.trophyIconItem {
			.xf-klUITrophyIconItemPostbit();
			cursor: pointer;
			color: inherit;
			text-decoration: none;
		}
	}

	&.trophyShowcase--profile {
		.xf-klUITrophyShowcaseProfile();

		.trophyIconItem {
			.xf-klUITrophyIconItemProfile();
			cursor: pointer;
			color: inherit;
			text-decoration: none;
		}
	}
}

.dataList-cell--first {
	.inputChoices label.iconic:not(.iconic--labelled) > input + i {
		top: -.7em;
	}
}

.block-footer {
	.selected-amount {
		&.error {
			color: red;
		}
	}
}';
	return $__finalCompiled;
}
);