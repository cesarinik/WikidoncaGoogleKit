Wikidonca Google Kit
==========
Valorizzare la variabile $wgWikidoncaGoogleKitAccount in LocalSettings.php con il proprio ID di monitoraggio.
Es.
$wgWikidoncaGoogleKitAccount = 'UA-12345678-9';

Per far funzionare i banner è sufficiente dichiarare le tre variabili $wgWikidoncaGoogleKitBannerSuperiore, $wgWikidoncaGoogleKitBannerInferiore, $wgWikidoncaGoogleKitBannerLaterale, nel file LocalSettings.php con i codici di Google AdSense (è anche possibile inserire div e stili)
Es.
`
$wgWikidoncaGoogleKitBannerSuperiore = '<div style="text-align:center; margin:10px 0px;"><script src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:block" data-full-width-responsive="true" data-ad-client="ca-pub-XXXXXXXXXXXXXXXX" data-ad-slot="XXXXXXXXXX" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';
`
