<?php
/**
 * Shibalike iframe pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
<style>
body {
    padding:0;
    margin: 0 10px 0;
}
.elgg-page-default {
    width:auto; 
    min-width:0;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
    width: auto;
    margin: 0;
}
.elgg-page-body {
    background:#fff;
    margin:0;
    padding: 0;
}
</style>
</head>
<body>
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/body', $vars); ?>
		</div>
	</div>
</div>
<script>
$(function () {
    $('form, a').attr('target', '_top');
    $('input[name=persistent]').parent().remove();
});
</script>
</body>
</html>