<?php
// FROM HASH: dcea42e4574857c4d14ddde514dc76fc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('fs_web_ranking_css_helper.less', $__vars) . '

// declare variables.
@webRankingNavItemHeight: 42px;

.webRankingViewNav--item {
  &:hover, &.is-active {
    background-color: @xf-paletteColor1;
    a {
      text-decoration: none;
      .xf-link();
    }
  }
}

.webRankingViewNav {
  .webRankingViewNav--item {
    a {
      padding: @xf-paddingLarge;
      display: flex;

      .webRankingViewNav--itemText {
        flex: 1;
      }
    }

    &:last-child {
      a {
        .m-borderBottomRadius(@xf-borderRadiusMedium - 1);
      }
    }

    &.is-active {
      a {
        border-left: 2px solid @xf-borderColorFeature;
      }
    }

    .badge {
      align-self: center;
    }
  }
}

.webRankingCover-header {
  .webRankingCover {
    .m-borderTopRadius(@xf-borderRadiusSmall + 1);
  }
}

.webRankingCoverFrame {
  width: 100%;
  height: 205px;
  overflow: hidden;
  position: relative;

  .m-transition(height, 200ms, ease-in);

  &.webRankingCoverFrame--setup {
    background-color: @xf-contentAccentBg;
    .loader--line-scale {
      > div {
        background-color: @xf-textColorAccentContent;
      }
    }
  }

  a {
    &:hover {
      text-decoration: none;
    }
  }

  .webRankingCover--img {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;

    .m-transition(top);
  }

  &.webRankingCoverFrame--setup {
    .webRankingCover--img {
      .m-transition(none);
    }
  }

  .webRankingCover--guide {
    position: absolute;
    font-size: @xf-fontSizeSmall;
    padding: @xf-paddingMedium @xf-paddingLarge;
    background-color: rgba(0,0,0,.4);
    border-radius: @xf-borderRadiusMedium;
    color: @xf-paletteNeutral1;

    .m-dropShadow();

    &:before {
      .m-faBase();
      .m-faContent("\\f047");
      margin-right: @xf-paddingSmall;
    }
  }

  // fixed flash text while cover editor
  .cropControls {
    display: none;
  }
}

.timePicker--wrap {
  display: block;
  padding: @xf-paddingLarge;
  box-sizing: border-box;

  .timePicker--wrap--text {
    font-size: @xf-fontSizeNormal;
  }
}

.flex--row {
  display: flex;
  flex-direction: row;
}

.flex--grow {
  flex-grow: 1;
}

// webRanking Prevew Customized
.tooltip--preview {
  .tooltip-content {
    .tooltip-expanded {
      max-height: 255px;
      margin: -@xf-paddingMedium;

      .memberTooltip-avatar {
        padding-right: 10px;
      }

      .webRankingCoverFrame {
        height: 90px;
        font-size: 25px;
      }

      .memberTooltip-actions {
        display: flex;
      }
    }

    .memberTooltip-name {
      font-size: @xf-fontSizeLarge;
    }

    .webRankingAvatar {
      .m-avatarSize(50px);
    }
  }
}

.webRankingCoverFrame,
.webRankingAvatar--default {
  .m-webRankingFlexCenter();

  font-size: 50px;
}

// webRanking avatar default (text dynamic)
.webRankingAvatar {
  display: flex;
  border-radius: @xf-fs_avatarBorderRadius;
  overflow: hidden;

  &:hover {
    text-decoration: none;
  }

  img {
    width: 100%;
  }
}

// SIDEBAR webRanking AVATAR
.contentRow-figure {
  .webRankingAvatar {
    .m-avatarSize(24px);
    border-radius: @xf-fs_avatarBorderRadius;
  }
}

.webRankingAvatar--default {
  text-decoration: none;
}

.p-body-sideNav {
  .webRankingAvatar {
    .m-avatarSize(@xf-sidebarWidth);
  }
}

.p-body-sideNavInner {
  &.is-active,
  &.is-transitioning {
    .webRankingAvatar-block {
      .webRankingAvatar {
        width: 100%;
        height: 100%;
        border-radius: 0;
      }
    }
  }
}

.webRankingHeader-navList {
  .webRankingViewNav--item, .webRankingHeader-navList--user {
    padding: @xf-paddingMedium;
  }

  .hScroller-action {
    .m-hScrollerActionColorVariation(@xf-contentHighlightBg, @xf-textColorFeature, @xf-textColorEmphasized);
  }

  .webRankingViewNav--item {
    height: @webRankingNavItemHeight;
    box-sizing: border-box;
    line-height: (@webRankingNavItemHeight + @xf-paddingMedium * 2)/2;
    padding-left: @xf-paddingLargest;
    padding-right: @xf-paddingLargest;

    &.is-active {
      border-bottom: 2px solid @xf-borderColorFeature;
      background-color: @xf-paletteColor1;

      a {
        background-color: transparent;
      }
    }
  }

  .webRankingHeader-navList--user {
    min-width: 200px;
    justify-content: flex-end;
  }
}

.webRankingBbCode--wrapper {
  max-width: 320px;

  .listInline {
    margin: 0 !important;
  }
}

.webRankingMembers {
  .contentRow {
    .avatar {
      .m-avatarSize(66px);
    }
  }
}

// 900px
@media (min-width: @xf-responsiveWide) {
  .webRankingHeader-navList {
    .hScroller {
      display: none !important;
    }

    .webRankingHeader-navList--user {
      justify-content: flex-start;
    }
  }
}

// 900px
@media (max-width: @xf-responsiveWide) {
  .webRankingHeader-navList {
    .hScroller {
      display: block !important;
    }
  }

  .webRankingCover-header {
    position: relative;
    .webRankingCover {
      .m-borderTopRadius(0);
    }

    .webRankingHeader-navList--user {
      position: absolute;
      bottom: @webRankingNavItemHeight;
      right: 0;

      .button--webRankingJoin,
      .button--webRankingAlerts {
        display: none;
      }

      .button {
        &.menuTrigger {
          border: 1px solid @xf-borderColor;
          border-top-left-radius: @xf-borderRadiusMedium !important;
          border-bottom-left-radius: @xf-borderRadiusMedium !important;
        }
      }
    }
  }
}

.tooltip-webRanking--inner {
  .memberTooltip-avatar {
    width: 60px;
  }
}

.webRankingBadge {
  display: flex;
  align-items: center;
  padding: @xf-paddingMedium;
  background-color: @xf-inlineModHighlightColor;
  border-radius: @xf-borderRadiusMedium;

  &.has-cover {
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: relative;
  }

  .webRankingBadge-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,.3);
    border-radius: @xf-borderRadiusMedium;
  }

  .webRankingAvatar {
    width: 28px;
    height: 28px;
    min-width: 28px;
    z-index: 1;
  }

  .webRankingBadge-name {
    margin-left: @xf-paddingMedium;
    z-index: 1;
    color: #fff;
    text-overflow: ellipsis;
    overflow: hidden;
    width: ~"calc(100% - 28px)";
    white-space: nowrap;
    &:hover {
      text-decoration: none;
    }
  }
}

.linkAlbums-fields {
  .input {
    + .input {
      margin-top: @xf-paddingLarge;
    }
  }
}

.main_state_box{
	display: flex;
	justify-content: space-between;
	width: 100%;
	padding: 7px 0px
}

.gridCard--containers{
	width: 80%;	
}

.main_gridCard_containers{
	display: flex;
	justify-content: center;
}';
	return $__finalCompiled;
}
);