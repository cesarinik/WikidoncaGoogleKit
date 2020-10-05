<?php

class WikidoncaGoogleKitHooks {
	function BannerPagina ( OutputPage $out, Skin $skin ) {
			global $wgWikidoncaGoogleKitBannerSuperiore, $wgWikidoncaGoogleKitBannerInferiore;
			$out->prependHTML( $wgWikidoncaGoogleKitBannerSuperiore );
			$out->addHTML( $wgWikidoncaGoogleKitBannerInferiore );
		}
		function BannerLaterale ( $skin, &$bar ) {
			global $wgWikidoncaGoogleKitBannerLaterale;
			$bar[ 'Ads' ] = $wgWikidoncaGoogleKitBannerLaterale;
			return true;
	}
	
	function CodiceAnalytics( Skin $skin, &$text = '' ) {
		global $wgWikidoncaGoogleKitAccount, $wgWikidoncaGoogleKitIPAnonimo, $wgWikidoncaGoogleKitAltroCodice,
			   $wgWikidoncaGoogleKitIgnoraNamespaceID, $wgWikidoncaGoogleKitIgnoraPagine, $wgWikidoncaGoogleKitIgnoraSpeciali;

		if ( $skin->getUser()->isAllowed( 'noanalytics' ) ) {
			$text .= "<!-- Inserimento codice Analytics disabilitato per questo utente. -->\r\n";
			return true;
		}

		if ( count( array_filter( $wgWikidoncaGoogleKitIgnoraSpeciali, function ( $v ) use ( $skin ) {
				return $skin->getTitle()->isSpecial( $v );
			} ) ) > 0
			|| in_array( $skin->getTitle()->getNamespace(), $wgWikidoncaGoogleKitIgnoraNamespaceID, true )
			|| in_array( $skin->getTitle()->getPrefixedText(), $wgWikidoncaGoogleKitIgnoraPagine, true ) ) {
			$text .= "<!-- Inserimento codice Analytics disabilitato per questa pagina. -->\r\n";
			return true;
		}

		$appended = false;

		if ( $wgWikidoncaGoogleKitAccount !== '' ) {
			$text .= <<<EOD
<script async src='https://www.googletagmanager.com/gtag/js?id=
EOD
. $wgWikidoncaGoogleKitAccount . <<<EOD
'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '
EOD
. $wgWikidoncaGoogleKitAccount . <<<EOD
');
</script>
EOD;
			$appended = true;
		}
		if ( $wgWikidoncaGoogleKitAltroCodice !== '' ) {
			$text .= $wgWikidoncaGoogleKitAltroCodice . "\r\n";
			$appended = true;
		}
		if ( !$appended ) {
			$text .= "<!-- Nessun codice Analytics configurato. -->\r\n";
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
