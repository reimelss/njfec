    <?php PC_Hooks::pc_after_opening_footer_tag(); /* Framework hook wrapper */ ?>

<div class="top_footer" style="clear: both;">
	<div class="sponserSection">
		<?php echo do_shortcode('[logooos columns="4" itemsheightpercentage="1.00" backgroundcolor="transparent" layout="slider" category="46" orderby="date" order="DESC" marginbetweenitems="25px" tooltip="enabled" responsive="enabled" grayscale="disabled" border="disabled" borderradius="logooos_no_radius" autoplay="true" transitioneffect="scroll" scrollduration="1000" pauseduration="9000" buttonsbordercolor="#DCDCDC" buttonsbgcolor="#FFFFFF" buttonsarrowscolor="lightgray" ]'); ?>
	</div>
</div>


    <?php get_sidebar( 'footer' ); // Adds support for the four footer widget areas ?>


	</footer>

</div><!-- #body-container -->
<style type="text/css">
	.sponserSection {
	    background: #fff;
	}
	div.logooos div.logooos_item a {
		width: 50% !important;
		height: 50% !important;
		background-size: 50% !important;
	}
</style>
<?php wp_footer(); ?>

<?php PC_Hooks::pc_after_closing_footer_tag(); /* Framework hook wrapper */ ?>


</body>
</html>