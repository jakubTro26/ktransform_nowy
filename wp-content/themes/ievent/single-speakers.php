


<head class="test"></head>
<?php 
global $ievent_data;

    
	if ($ievent_data['speaker_view']=='single-page'):
		//get_header();
		$class_single='speaker-single-page';
	elseif ($ievent_data['speaker_view']=='pop-up'):
		$class_single='speaker-pop-up';
	endif;

		
?>

 <!-- BOF Main Content -->
    <div id="main" role="main" class="main">
        <div id="primary" class="content-area">
            <div class="container">
                <div class="sixteen columns jx-ievent-padding f <?php echo $class_single; ?>">
                <div id="home" class="jx-ievent-page-titlebar">
                    <div class="page-titlebar-bg parallax-no" style="background: url(&quot;https://kongresksiegowych.pl/wp-content/uploads/2018/04/kamil-gliwinski-568269-unsplash.jpg&quot;) center center;"></div>
                    <!-- Background Image -->                    
                    <div class="container">
                                                <div class="jx-ievent-page-titlebar-items">
                            <div class="sixteen columns left">
                                <div class="jx-ievent-breadcrumb"><a href="https://kongrestransformacji.pl/" rel="v:url" property="v:title">Home</a><span class="current">Kontakt</span></div>
                            </div>  
                            <!-- Page Title-->                           
                        </div>
                    	                    </div>
                </div>
                
                	<?php while ( have_posts() ) : the_post(); ?>
            
                        <?php get_template_part( 'template-parts/content', 'speakers' ); ?>
            
                    <?php endwhile; // End of the loop. ?>                    

                </div>

            </div>
            <!-- EOF Container -->
        </div><!-- #primary -->
    </div>
    
<?php 


	if ($ievent_data['speaker_view']=='single-page'):
		get_footer();
	endif;

 ?>
