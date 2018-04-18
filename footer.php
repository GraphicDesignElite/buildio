
</div>

<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="shortcut">
					<?php $bookmarkletdialog = yourls__( 'Save this as a bookmark.', 'isq_translation') // Can't put it where it belongs as there'd be too much char escaping ?>
					<p class="bookmarklet-container"><a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo YOURLS_SITE . '/index.php'; ?>',l=d.location,p='?url='+enc(l.href)+'&title='+enc(d.title)+'&keyword='+s2,u=f+p;try%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);%7D)();" onClick="alert('<?php echo $bookmarkletdialog; ?>'); return false;" class="bookmarklet button"><span class="icon-move"><?php include('public/images/move.svg'); ?></span><?php yourls_e( 'Create Bookarklet', 'isq_translation') ?></a></p>
					<p><small><?php yourls_e( 'Save or Drag the bookmarklet to your Bookmarks to create iBuild links from anywhere.', 'isq_translation') ?> </small></p>
					<ul class="footer-links">
						<li><a href="https://www.domain.build/terms" target="_blank" class="terms-link">Terms of Use</a></li>
						<li><a href="https://www.domain.build/privacy" target="_blank" class="terms-link">Privacy Policy</a></li>
						<?php 
							$auth = yourls_is_valid_user(); 
							$uri = $_SERVER['REQUEST_URI'];
							$isAdminArea = strpos($uri, 'admin');
						 
						?>
						<?php if($auth == 1 && $isAdminArea) : ?>
							<li><a href="/admin/plugins.php" target="_blank" class="terms-link">Plugins</a></li>
							<li><a href="/admin/tools.php" target="_blank" class="terms-link">Tools</a></li>
							<li><a href="/admin/tools.php?action=logout" target="_blank" class="terms-link">Log Out Admin</a></li>
							
						<?php endif; ?>
						<li><a href="https://www.domain.build/contact-us" target="_blank" class="terms-link">Contact Us</a></li>
					</ul>
					<p><small class="copyright">&copy; 2018 .BUILD.</small></p>
					
					
				</div>
				<?php //if ( !empty(LUX_SETTINGS::$recaptcha['sitekey']) && !empty(LUX_SETTINGS::$recaptcha['secret']) ) { ?>
					<!-- <p class="recaptcha-cookie"><?php // yourls_e('This site uses cookies for Google reCAPTCHA','isq_translation')?>.<p> -->
				<?php //}; ?>
			</div>
		</div>
	</div>
</footer>
</section><!-- end push menu wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js" type="text/javascript"></script>

<script src="<?php yourls_site_url(); ?>/js/common.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/js/jquery.notifybar.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/js/infos.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/js/jquery.tablesorter.min.js?v=1.7.3" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/js/insert.js?v=1.7.3" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/js/share.js?v=1.7.3" type="text/javascript"></script>
<script src="<?php yourls_site_url(); ?>/public/js/clipboard.min.js"></script>
<script src="<?php yourls_site_url(); ?>/public/js/functions.js"></script>
<script src="<?php yourls_site_url(); ?>/public/js/push.js"></script>
<script src="<?php yourls_site_url(); ?>/public/js/app.js"></script>


<?php global $dependencies; ?>

<?php
if ( in_array( 'reCAPTCHA', $dependencies ) ) { ?>
	<script src="https://www.google.com/recaptcha/api.js"></script>
<?php } ?>


</body>
</html>
