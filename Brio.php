<?php // @codingStandardsIgnoreLine
if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'Brio' );
	$wgMessagesDirs['Brio'] = __DIR__ . '/i18n';
	return true;
} else {
	die( 'This version of the Brio skin requires MediaWiki 1.25+' );
}
