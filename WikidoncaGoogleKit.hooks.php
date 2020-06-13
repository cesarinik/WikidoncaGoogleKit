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
<script>
  (function(i,s,o,g,r,a,m){i['WikidoncaGoogleKitObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '
EOD
. $wgWikidoncaGoogleKitAccount . <<<EOD
', 'auto');

EOD
. ( $wgWikidoncaGoogleKitIPAnonimo ? "  ga('set', 'anonymizeIp', true);\r\n" : "" ) . <<<EOD
  ga('send', 'pageview');

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
