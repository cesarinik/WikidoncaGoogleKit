<?php

class WikidoncaGoogleKitHooks {
	public static function BannerPagina( OutputPage $out, Skin $skin ) {
			global $wgWikidoncaGoogleKitBannerSuperiore, $wgWikidoncaGoogleKitBannerInferiore;
			$out->prependHTML( $wgWikidoncaGoogleKitBannerSuperiore );
			$out->addHTML( $wgWikidoncaGoogleKitBannerInferiore );
		}
	public static function CodiceAnalytics( Skin $skin, &$text = '' ) {
		global $wgWikidoncaGoogleKitAccount, $wgWikidoncaGoogleKitIPAnonimo, $wgWikidoncaGoogleKitIgnoraNamespaceID, $wgWikidoncaGoogleKitIgnoraPagine, $wgWikidoncaGoogleKitIgnoraSpeciali;

		if ( $skin->getUser()->isAllowed( 'noanalytics' ) ) {
			$text .= "\r\n";
			return true;
		}

		if ( count( array_filter( $wgWikidoncaGoogleKitIgnoraSpeciali, function ( $v ) use ( $skin ) {
				return $skin->getTitle()->isSpecial( $v );
			} ) ) > 0
			|| in_array( $skin->getTitle()->getNamespace(), $wgWikidoncaGoogleKitIgnoraNamespaceID, true )
			|| in_array( $skin->getTitle()->getPrefixedText(), $wgWikidoncaGoogleKitIgnoraPagine, true ) ) {
			$text .= "\r\n";
			return true;
		}

		$appended = false;

		if ( $wgWikidoncaGoogleKitAccount !== '' ) {
			$ipAnonimo = $wgWikidoncaGoogleKitIPAnonimo ? "'anonymize_ip': true" : '';

			$text .= <<<EOD
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id={$wgWikidoncaGoogleKitAccount}'></script>		   
<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '{$wgWikidoncaGoogleKitAccount}'{$ipAnonimo});
</script>
EOD;
			$appended = true;
		}
		if ( !$appended ) {
			$text .= "\r\n";
		}
		return true;
	}

	public static function TestsList( array &$files ) {
		$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/tests/' );
		$ourFiles = array();
		foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
			if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
				$ourFiles[] = $fileInfo->getPathname();
			}
		}

		$files = array_merge( $files, $ourFiles );
		return true;
	}
}
?>
