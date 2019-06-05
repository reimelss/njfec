    <?php PC_Hooks::pc_after_opening_footer_tag(); /* Framework hook wrapper */ ?>

<div class="top_footer" style="clear: both;">
	<div class="sponserSection">
		<?php echo do_shortcode('[logooos columns="3" itemsheightpercentage="0.75" backgroundcolor="transparent" layout="grid" category="46" orderby="date" order="DESC" marginbetweenitems="5px" tooltip="enabled" responsive="enabled" grayscale="disabled" border="enabled" bordercolor="#DCDCDC" borderradius="logooos_no_radius" hovereffect="effect1" hovereffectcolor="#DCDCDC" ]'); ?>
	</div>
</div>


    <?php get_sidebar( 'footer' ); // Adds support for the four footer widget areas ?>


	</footer>

</div><!-- #body-container -->
<?php wp_footer(); ?>

<?php PC_Hooks::pc_after_closing_footer_tag(); /* Framework hook wrapper */ ?>


</body>
</html>