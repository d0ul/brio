<?php

//phpcs:ignore
class BrioHooks {
	/**
	 * @since 1.17.0
	 * @param OutputPage $out
	 * @param Skin $sk
	 * @param array &$bodyAttrs
	 */
	public static function onOutputPageBodyAttributes( OutputPage $out, Skin $sk, &$bodyAttrs ) {
		if ( $sk->getSkinName() === 'brio' ) {
			$bodyAttrs['class'] .= ' Brio width-size';
		}
	}

	/**
	 * Set up user preferences specific to the Brio skin.
	 *
	 * @param User $user user
	 * @param Preferences &$preferences preferences
	 */
	public static function onGetPreferences( $user, &$preferences ) {
		global $wgBrioAdSetting, $wgBrioAdGroup;

		$service = MediaWiki\MediaWikiServices::getInstance();
		$userGroupManager = $service->getUserGroupManager();
		$userGroups = $userGroupManager->getUserGroups( $user );
		$permissionManager = $service->getPermissionManager();

		$preferences['brio-layout-width'] = [
			'type' => 'select',
			'label-message' => 'brio-pref-layout-width',
			'section' => 'brio/layout',
			'options' => [
				wfMessage( 'brio-layout-select-1000' )->text() => '1000px',
				wfMessage( 'brio-layout-select-1100' )->text() => '1100px',
				wfMessage( 'brio-layout-select-1200' )->text() => null,
				wfMessage( 'brio-layout-select-1300' )->text() => '1300px',
				wfMessage( 'brio-layout-select-1400' )->text() => '1400px',
				wfMessage( 'brio-layout-select-1500' )->text() => '1500px',
				wfMessage( 'brio-layout-select-1600' )->text() => '1600px',
			],
			'help-message' => 'brio-pref-layout-width-help',
			'default' => null
		];

		$preferences['brio-layout-navfix'] = [
			'type' => 'toggle',
			'label-message' => 'brio-pref-layout-navfix',
			'section' => 'brio/layout',
		];

		$preferences['brio-layout-sidebar'] = [
			'type' => 'toggle',
			'label-message' => 'brio-pref-layout-sidebar',
			'section' => 'brio/layout',
		];

		$preferences['brio-layout-controlbar'] = [
			'type' => 'toggle',
			'label-message' => 'brio-pref-layout-controlbar',
			'section' => 'brio/layout',
		];

		if (
			isset( $wgBrioAdSetting['client'] ) && $wgBrioAdSetting['client'] &&
			isset( $wgBrioAdGroup ) && $wgBrioAdGroup == 'differ'
		) {
			if (
				isset( $wgBrioAdSetting['belowarticle'] ) && $wgBrioAdSetting['belowarticle'] &&
				$permissionManager->userHasRight( $user, 'blockads-belowarticle' )
			) {
				$preferences['brio-ads-morearticle'] = [
					'type' => 'toggle',
					'label-message' => 'brio-pref-ads-belowarticle',
					'section' => 'brio/ads',
				];
			}

			if (
				isset( $wgBrioAdSetting['header'] ) && $wgBrioAdSetting['header'] &&
				$permissionManager->userHasRight( $user, 'blockads-header' )
			) {
				$preferences['brio-ads-header'] = [
					'type' => 'toggle',
					'label-message' => 'brio-pref-ads-header',
					'section' => 'brio/ads',
				];
			}

			if (
				isset( $wgBrioAdSetting['right'] ) && $wgBrioAdSetting['right'] &&
				$permissionManager->userHasRight( $user, 'blockads-right' )
			) {
				$preferences['brio-ads-rightads'] = [
					'type' => 'toggle',
					'label-message' => 'brio-pref-ads-right',
					'section' => 'brio/ads',
				];
			}

			if (
				isset( $wgBrioAdSetting['bottom'] ) && $wgBrioAdSetting['bottom'] &&
				$permissionManager->userHasRight( $user, 'blockads-bottom' )
			) {
				$preferences['brio-ads-bottom'] = [
					'type' => 'toggle',
					'label-message' => 'brio-pref-ads-bottom',
					'section' => 'brio/ads',
				];
			}
		}

		$preferences['brio-color-main'] = [
			'type' => 'text',
			'label-message' => 'brio-pref-color-main',
			'section' => 'brio/color',
			'help-message' => 'brio-pref-color-main-help'
		];

		$preferences['brio-color-second'] = [
			'type' => 'text',
			'label-message' => 'brio-pref-color-second',
			'section' => 'brio/color',
			'help-message' => 'brio-pref-color-second-help'
		];

		$preferences['brio-font'] = [
			'type' => 'selectorother',
			'label-message' => 'brio-pref-fonts',
			'section' => 'brio/font',
			'options' => [
				wfMessage( 'brio-font-name-default' )->text() => null,
				wfMessage( 'brio-font-name-pretendard' )->text() => 'Pretendard',
				wfMessage( 'brio-font-name-noto-sans-kr' )->text() => 'Noto Sans KR',
				wfMessage( 'brio-font-name-noto-serif-kr' )->text() => 'Noto Serif KR',
				wfMessage( 'brio-font-name-spoqa-han-sans' )->text() => 'Spoqa Han Sans',
				wfMessage( 'brio-font-name-nanum-gothic' )->text() => 'Nanum Gothic',
				wfMessage( 'brio-font-name-nanum-myeongjo' )->text() => 'Nanum Myeongjo',
				wfMessage( 'brio-font-name-malgun-gothic' )->text() => 'Malgun Gothic'
			],
			'help-message' => 'brio-pref-fonts-help',
			'default' => null,
		];

		$preferences['brio-dark'] = [
			'type' => 'select',
			'label-message' => 'brio-pref-dark',
			'section' => 'brio/color',
			'options' => [
				wfMessage( 'brio-dark-default' )->text() => null,
				wfMessage( 'brio-dark-dark' )->text() => 'dark',
				wfMessage( 'brio-dark-light' )->text() => 'light'
			],
			'help-message' => 'brio-pref-dark-help',
			'default' => null
		];
	}
}
