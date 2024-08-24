<?php

use MediaWiki\MediaWikiServices;

class SkinBrio extends SkinTemplate {
	// @codingStandardsIgnoreStart
	public $skinname = 'brio';
	public $stylename = 'Brio';
	public $template = 'BrioTemplate';
	// @codingStandardsIgnoreEnd

	/**
	 * Page initialize.
	 *
	 * @param OutputPage $out OutputPage
	 */
	public function initPage( OutputPage $out ) {
		// @codingStandardsIgnoreLine
		global $wgSitename, $wgTwitterAccount, $wgLanguageCode, $wgNaverVerification, $wgLogo, $wgBrioEnableLiveRC, $wgBrioAdSetting, $wgBrioAdGroup, $wgBrioNavBarLogoImage;

		$user = $this->getUser();
		$services = MediaWikiServices::getInstance();
		$userOptionsLookup = $services->getUserOptionsLookup();
		/* uncomment if needs to use UserGroupManager
		$userGroupManager = $services->getUserGroupManager();
		$userGroups = $userGroupManager->getUserGroups( $user );
		*/

		$optionMainColor = $userOptionsLookup->getOption( $user, 'brio-color-main' );
		$optionSecondColor = $userOptionsLookup->getOption( $user, 'brio-color-second' );

		$mainColor = $optionMainColor ? $optionMainColor : $GLOBALS['wgBrioMainColor'];
		// @codingStandardsIgnoreLine
		$tempSecondColor = isset( $GLOBALS['wgBrioSecondColor'] ) ? $GLOBALS['wgBrioSecondColor'] : '#' . strtoupper( dechex( hexdec( substr( $mainColor, 1, 6 ) ) - hexdec( '1A1415' ) ) );
		$secondColor = $optionSecondColor ? $optionSecondColor : $tempSecondColor;
		$ogLogo = isset( $GLOBALS['wgBrioOgLogo'] ) ? $GLOBALS['wgBrioOgLogo'] : $wgLogo;
		if ( !preg_match( '/^((?:(?:http(?:s)?)?:)?\/\/(?:.{4,}))$/i', $ogLogo ) ) {
			$ogLogo = $GLOBALS['wgServer'] . $GLOBALS['wgLogo'];
		}

		$skin = $this->getSkin();

		parent::initPage( $out );

		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );

		if (
			!class_exists( ArticleMetaDescription::class ) ||
			!class_exists( Description2::class )
		) {
			// The validator complains if there's more than one description,
			// so output this here only if none of the aforementioned SEO
			// extensions aren't installed
			$out->addMeta( 'description', strip_tags(
				preg_replace( '/<table[^>]*>([\s\S]*?)<\/table[^>]*>/', '', $out->mBodytext ),
				'<br>'
			) );
		}
		$out->addMeta( 'keywords', $wgSitename . ',' . $skin->getTitle() );

		/* 네이버 웹마스터 도구 */
		if ( isset( $wgNaverVerification ) ) {
			$out->addMeta( 'naver-site-verification', $wgNaverVerification );
		}

		/* IOS 기기 및 모바일 크롬에서의 웹앱 옵션 켜기 및 상단바 투명화 */
		$out->addMeta( 'apple-mobile-web-app-capable', 'Yes' );
		$out->addMeta( 'apple-mobile-web-app-status-bar-style', 'black-translucent' );
		$out->addMeta( 'mobile-web-app-capable', 'Yes' );

		/* 모바일에서의 테마 컬러 적용 */
		// 크롬, 파이어폭스 OS, 오페라
		$out->addMeta( 'theme-color', $mainColor );
		// 윈도우 폰
		$out->addMeta( 'msapplication-navbutton-color', $mainColor );

		/* 트위터 카드 */
		$out->addMeta( 'twitter:card', 'summary' );
		if ( isset( $wgTwitterAccount ) ) {
			$out->addMeta( 'twitter:site', "@$wgTwitterAccount" );
			$out->addMeta( 'twitter:creator', "@$wgTwitterAccount" );
		}

		$modules = [
			'skins.brio.bootstrap',
			'skins.brio.layoutjs'
		];

		// Only load ad-related JS if ads are enabled in site configuration
		if ( isset( $wgBrioAdSetting['client'] ) && $wgBrioAdSetting['client'] ) {
			$modules[] = 'skins.brio.ads';
		}

		// Only load LiveRC JS is we have enabled that feature in site config
		if ( $wgBrioEnableLiveRC ) {
			$modules[] = 'skins.brio.liverc';
		}

		// Only load modal login JS for anons, no point in loading it for logged-in
		// users since the modal HTML isn't even rendered for them.
		if ( $skin->getUser()->isAnon() ) {
			$modules[] = 'skins.brio.loginjs';
		}

		$out->addModules( $modules );

		// @codingStandardsIgnoreStart
		$out->addInlineStyle(
			".Brio .nav-wrapper .navbar .form-inline .btn:hover,
		.Brio .nav-wrapper .navbar .form-inline .btn:focus,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link.active::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:hover::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:focus::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:active::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-footer .label,
		.Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn:hover,
		.Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn:focus,
		.Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn:active {
			background-color: $mainColor;
		}

		.Brio .nav-wrapper{
			background: linear-gradient(90deg, #3553A0, #3553A0, #4581C4);
		}

		.Brio .nav-wrapper .navbar .form-inline .btn:hover,
		.Brio .nav-wrapper .navbar .form-inline .btn:focus {
			border-color: $secondColor;
		}

		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link.active::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:hover::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:focus::before,
		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link:active::before {
			border-bottom: 2px solid $mainColor;
		}

		.Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-footer .label:hover,
		.Brio .nav-wrapper .navbar .navbar-nav .nav-item .nav-link:hover,
		.Brio .nav-wrapper .navbar .navbar-nav .nav-item .nav-link:focus,
		.dropdown-menu .dropdown-item:hover {
			background-color: $secondColor;
		}


		.Brio .content-wrapper #brio-bottombtn,
		.Brio .content-wrapper #brio-bottombtn:hover {
			background-color: $mainColor;
		}"
		);

		// navbar image settings
		if ( isset( $wgBrioNavBarLogoImage ) ) {
			$out->addInlineStyle(
				".Brio .nav-wrapper .navbar .navbar-brand {
					background: transparent url($wgBrioNavBarLogoImage) no-repeat scroll left center/auto 1.9rem;
				}
				@media screen and (max-width: 397px){
					.Brio .nav-wrapper .navbar .navbar-brand {
						background: transparent url($wgBrioNavBarLogoImage) no-repeat scroll left center/auto 1.5rem;
					}
				}"
			);
		}

		// layout settings
		$BrioUserWidthSettings = $userOptionsLookup->getOption( $user, 'brio-layout-width' );
		$BrioUserSidebarSettings = $userOptionsLookup->getOption( $user, 'brio-layout-sidebar' );
		$BrioUserNavbarSettings = $userOptionsLookup->getOption( $user, 'brio-layout-navfix' );
		$BrioUsercontrolbarSettings = $userOptionsLookup->getOption( $user, 'brio-layout-controlbar' );

		if ( isset( $BrioUserNavbarSettings ) && $BrioUserNavbarSettings ) {
			$out->addInlineStyle(
				".navbar-fixed-top {
					position: absolute;
				}"
			);
		}

		if ( isset( $BrioUserSidebarSettings ) && $BrioUserSidebarSettings ) {
			$out->addInlineStyle(
				".Brio .content-wrapper .brio-content {
					margin-right: 0;
				}"
			);
		}

		if ( $BrioUserWidthSettings !== null ) {
			$out->addInlineStyle(
				".Brio .content-wrapper {
					max-width: $BrioUserWidthSettings;
				}

				.Brio .nav-wrapper .navbar {
					max-width: $BrioUserWidthSettings;
				}"
			);
		}

		if ( isset( $BrioUsercontrolbarSettings ) && $BrioUsercontrolbarSettings ) {
			$out->addInlineStyle(
				".Brio .content-wrapper #brio-bottombtn {
					display: none;
				}"
			);
		}

		// Font settings
		$BrioUserFontSettings = $userOptionsLookup->getOption( $user, 'brio-font' );
		if ( $BrioUserFontSettings !== null ) {
			$out->addInlineStyle(
				"body, h1, h2, h3, h4, h5, h6, b {
					font-family: $BrioUserFontSettings;
				}"
			);
		}

		// Ads setting
		if ( isset( $wgBrioAdSetting['client'] ) && $wgBrioAdSetting['client'] ) {
			// change ads option by rights
			if ( isset( $wgBrioAdGroup ) && $wgBrioAdGroup == 'differ' ) {
				if (
					isset( $wgBrioAdSetting['header'] ) && $wgBrioAdSetting['header'] &&
					$userOptionsLookup->getOption( $user, 'brio-ads-header' )
				) {
					$wgBrioAdSetting['header'] = null;
				}
				if (
					isset( $wgBrioAdSetting['right'] ) && $wgBrioAdSetting['right'] &&
					$userOptionsLookup->getOption( $user, 'brio-ads-right' )
				) {
					$wgBrioAdSetting['right'] = null;
				}
				if (
					isset( $wgBrioAdSetting['bottom'] ) && $wgBrioAdSetting['bottom'] &&
					$userOptionsLookup->getOption( $user, 'brio-ads-bottom' )
				) {
					$wgBrioAdSetting['bottom'] = null;
				}
				if (
					isset( $wgBrioAdSetting['belowarticle'] ) && $wgBrioAdSetting['belowarticle'] &&
					$userOptionsLookup->getOption( $user, 'brio-ads-belowarticle' )
				) {
					$wgBrioAdSetting['belowarticle'] = null;
				}
			}
		}

		$BrioDarkCss = "body, .Brio, .dropdown-menu, .dropdown-item, .Brio .nav-wrapper .navbar .form-inline .btn, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item .nav-link.active, .Brio .content-wrapper .brio-content .brio-content-main table.wikitable tr > th, .Brio .content-wrapper .brio-content .brio-content-main table.wikitable tr > td, table.mw_metadata th, .Brio .content-wrapper .brio-content .brio-content-main table.infobox th, #preferences fieldset:not(.prefsection), #preferences div.mw-prefs-buttons, .navbox, .navbox-subgroup, .navbox > tbody > tr:nth-child(even) > .navbox-list {
			background-color: #000;
			color: #DDD;
		}

		.brio-content-header, .brio-footer, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-footer, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item, .Brio .content-wrapper .brio-content .brio-content-header, .Brio .content-wrapper .brio-footer, .editOptions, html .wikiEditor-ui-toolbar, #pagehistory li.selected, .mw-datatable td, .Brio .content-wrapper .brio-content .brio-content-main table.wikitable tr > td, table.mw_metadata td, .Brio .content-wrapper .brio-content .brio-content-main table.wikitable, .Brio .content-wrapper .brio-content .brio-content-main table.infobox, #preferences, .navbox-list, .dropdown-divider {
			background-color: #1F2023;
			color: #DDD;
		}

		.Brio .content-wrapper .brio-content .brio-content-main, .mw-datatable th, .mw-datatable tr:hover td, textarea, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-content, div.mw-warning-with-logexcerpt, div.mw-lag-warn-high, div.mw-cascadeprotectedwarning, div#mw-protect-cascadeon {
			background-color: #000;
		}

		.Brio .content-wrapper .brio-content .brio-content-header .title>h1, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-content .live-recent-list .recent-item, caption { color: #DDD; }

		.btn-secondary { background: transparent; color: #DDD; }

		#pagehistory li { border: 0; }

		.Brio .content-wrapper .brio-footer, .Brio .content-wrapper .brio-content .brio-content-header, .Brio .content-wrapper .brio-content .brio-content-main, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-footer, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-content, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-header .nav .nav-item + .nav-item, .Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn:hover, .Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn:focus, .Brio .content-wrapper .brio-content .brio-content-header .content-tools .tools-btn, .dropdown-menu, .dropdown-divider, .Brio .content-wrapper .brio-content .brio-content-main fieldset, hr, .Brio .content-wrapper .brio-sidebar .live-recent-wrapper .live-recent .live-recent-content .live-recent-list li, .mw-changeslist-legend, .Brio .content-wrapper .brio-content .brio-content-header .content-tools { border-color: #555; }

		.flow-post, .Brio .content-wrapper .brio-content .brio-content-main .toc .toctext { color: #DDD; }
		.flow-topic-titlebar { color: #000; }
		.flow-ui-navigationWidget { color: #FFF; }
		.Brio .content-wrapper .brio-content .brio-content-main .toccolours, .Brio .content-wrapper .brio-content .brio-content-main .toc ul, .Brio .content-wrapper .brio-content .brio-content-main .toc li { background-color: #000; }
		.Brio .content-wrapper .brio-content .brio-content-main .toc .toctitle { background-color: #1F2023; }";

		$BrioUserDarkSetting = $userOptionsLookup->getOption( $user, 'brio-dark' );
		if ( $BrioUserDarkSetting === 'dark' ) {
			$out->addInlineStyle( $BrioDarkCss );
		} elseif ( $BrioUserDarkSetting === null ) {
			$out->addInlineStyle( "@media (prefers-color-scheme: dark) { $BrioDarkCss }" );
		}

		// @codingStandardsIgnoreEnd
		$this->setupCss( $out );
	}

	/**
	 * Setup skin CSS.
	 *
	 * @param OutputPage $out OutputPage
	 */
	public function setupCss( OutputPage $out ) {
		$out->addHeadItem(
			'font-awesome',
			// @codingStandardsIgnoreLine
			'<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.13.1/css/all.css" />'
		);

		$out->addHeadItem(
			'font-awesome-shims',
			// @codingStandardsIgnoreLine
			'<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.13.1/css/v4-shims.css" />'
		);

		$out->addHeadItem(
			'webfonts',
			// @codingStandardsIgnoreLine
			'<link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css" /><link href="https://fonts.googleapis.com/css?family=Dokdo|Gaegu|Nanum+Gothic|Nanum+Gothic+Coding|Nanum+Myeongjo|Noto+Serif+KR|Noto+Sans+KR&display=swap&subset=korean" rel="stylesheet">'
		);

		$out->addHeadItem(
			'share-api-polyfill',
			// @codingStandardsIgnoreLine
			'<script async src="https://unpkg.com/share-api-polyfill/dist/share-min.js"></script>'
		);
		$out->addModuleStyles( [ 'skins.brio.styles' ] );
	}
}
