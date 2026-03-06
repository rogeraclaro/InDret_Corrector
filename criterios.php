<?php
/**
 * Template Name: Criterios
 * @package InDret
 */

get_header(); ?>

<div id="content" class="site-content container-fluid editorial-interior">
	<div id="primary" class="content-area container">
		<main id="main" class="site-main col-md-12" role="main">
		<div class="fitxa">
		<?php while ( have_posts() ) : the_post(); ?>	
			<div class="entry-content">
			
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<h1 class="entry-title-interior">
						<? global $language;
							if($language=="es")
							{?>
								<?php the_title(); ?>
							<?}
							else if($language=="ca")
							{?>
								<?php the_field('titol_ca'); ?>
							<?}
							else if($language=="en")
							{?>
								<?php the_field('titol_en'); ?>
							<?}		
						?>
						</h1>
						<div class="separa-petit"></div>
					</div>
					<div class="col-md-2">
					</div>
				</div>

				<div class="row">
					<div class="col-md-2">
						<div class="dates">
						</div>
					</div>
					<div class="col-md-8">
						<div class="costext">
						<? global $language;
							if($language=="es")
							{?>
								<?php the_content(); ?>
							<?}
							else if($language=="ca")
							{?>
								<?php the_field('catala'); ?>
							<?}
							else if($language=="en")
							{?>
								<?php the_field('english'); ?>
							<?}		
						?>	
						</div>
					</div>
					<div class="col-md-2">
					</div>
				</div>

			</div><!-- .entry-content -->
		<?php endwhile; // end of the loop. ?>
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php get_footer(); ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

function formatBytes(a,b){if(0==a)return"0 Bytes";var c=1024,d=b||0,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f]}

var KB = '<?php the_field('tamano_adjunto'); ?>';
var gg = formatBytes(KB);
console.log ('kb:', gg);
$(".pes_en_kb").html(gg);

function cuentadescargas() {
	var descargas_old = '<?php the_field('ranking_descargas'); ?>';
	if (descargas_old == '' || descargas_old == 'NULL') {
		descargas_old = 0;
	}
	console.log ('descargas:', descargas_old);
	$(".descargas").html(descargas_old);
}

cuentadescargas();

/*
var count = document.getElementById('nomsautor').innerHTML.split(' ').length;
if (count == 3) {
	}
else {
}
console.log ('paraules:', count);
*/

	$('span.nomsautor').each(function(){
	    var text = $(this).text().split(/\s+/);
	    //if(text.length < 2)
	    //  return;
	    if(text.length == 2) {
	    text[1] = '<span class="majusc">'+text[1]+'</span>';
	    $(this).html( text.join(' ') );
		}
	    else if(text.length == 3) {
	    text[1] = '<span class="majusc">'+text[1]+'</span>';
	    text[2] = '<span class="majusc">'+text[2]+'</span>';
	    $(this).html( text.join(' ') );
		}
		else if(text.length == 4) {
		text[1] = '<span class="majusc">'+text[1]+'</span>';
	    text[2] = '<span class="majusc">'+text[2]+'</span>';
	    text[3] = '<span class="majusc">'+text[3]+'</span>';
	    $(this).html( text.join(' ') );
		}
	    console.log(text);
	});


});
</script>