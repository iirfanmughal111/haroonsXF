<?php
// FROM HASH: 82a1c70b114b5834518f4551cd013d73
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('fs_web_ranking_css_helper.less', $__vars) . '

.webRanking--counterList {
  
  .webRanking-displayFlex();
}

.webRankingListBlock {
  .webRankingList {
    margin-right: -@xf-paddingMedium + 1;
    margin-left: -@xf-paddingMedium + 1;
    flex-wrap: wrap;
	  padding:0px 5px;
  }

  &.webRankingListBlock--empty {
    .block-container {
        margin-bottom: @xf-paddingMedium;
      }
  }

  .block-filterBar {
    border-bottom-width: 0;
  }
}

.webRankingCover--wrapper {
  position: relative;

  .webRankingCoverFrame {
    height: 100%;
    font-size: 30px;
  }

  .webRankingCover--inlineMod {
    .m-dropShadow();
    background: rgba(0,0,0,.6);
    color: #ecf0ff;
    border-radius: 50%;

    position: absolute;
    top: @xf-paddingMedium;
    left: @xf-paddingMedium;
    width: 24px;
    height: 24px;
    font-size: 15px;
    padding-top: 2px;
    padding-left: 2px;
    text-align: center;
  }

  .iconic--noLabel {
    &:hover {
      cursor: pointer;
      input {
        + i:before {
          color: #FFFFFF;
        }
      }
    }
  }
}
.widget-webRanking {
  .contentRow-figure {
    .webRankingAvatar {
      .m-avatarSize(24px);
    }
  }
}

// 650px
@media(max-width: @xf-responsiveMedium) {
  .webRankingListBlock {
    border-radius: 0;
    border-left: none;
    border-right: none;

    .webRankingList {
      margin-right: -@xf-paddingLarge;
      margin-left: -@xf-paddingLarge;

      .gridCard {
        padding-left: 0;
        padding-right: 0;
      }
    }

    .gridCard--container {
      border-radius: 0;
      .gridCard--cover {
        .m-borderTopRadius(0);
      }
    }
  }
}
// 480px
@media(max-width: @xf-responsiveNarrow) {
}';
	return $__finalCompiled;
}
);