<?php
/**
 * op-unit-app:/testcase/FingerPrint.php
 *
 * @creation  2025-11-19
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara
 * @copyright Tomoaki Nagahara All right reserved.
 */

//	...
$fingerprint = OP()->Request('fingerprint');

?>
<style>
textarea {
	width: 100%;
	height: 10em;
}
</style>
<form method="post">
	<textarea name="fingerprint"><?= $fingerprint ?></textarea><br/>
	<button> Decrypt </button>
</form>
<script>
document.addEventListener("DOMContentLoaded", function() {
	var div = document.querySelector('div[data-content-hash]');
	document.querySelector('textarea[name="fingerprint"]').innerText = div.dataset.contentHash;
});

</script>
<?php
if( $fingerprint ){
	D( OP()->Decrypt($fingerprint) );
}
?>
