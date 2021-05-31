<?php global $ievent_data;?>
    <!-- BOF Footer -->
	<!-- EOF Main -->
    <?php if($ievent_data['checkbox_infobox']): ?>
		<?php if (shortcode_exists('info_box')):?>
            <?php echo do_shortcode('[info_box icon_1="vc_li vc_li-location" title_1="'.$ievent_data['venue_location'].'" info_1="'.$ievent_data['venue_address'].'" icon_2="vc_li vc_li-world" title_2="'.$ievent_data['venue_phone'].'" info_2="'.$ievent_data['venue_email'].'"][/info_box]'); ?>
        <?php endif; ?>
    <?php endif; ?>
    
    <footer class="jx-ievent-footer-1 jx-ievent-footer-section jx-ievent-container">      	
        
        <div class="jx-ievent-footer-widget">        
        	<div class="container">        	
                    <div class="four columns">
					
					<div role="form" class="wpcf7" id="wpcf7-f2272-o1" lang="pl-PL" dir="ltr">
                        <div class="screen-reader-response"><p role="status" aria-live="polite" aria-atomic="true"></p> <ul></ul></div>
                            <form action="/#wpcf7-f2272-o1" method="post" class="wpcf7-form init" novalidate="novalidate" data-status="init">
                                <div style="display: none;">
                                    <input type="hidden" name="_wpcf7" value="2272">
                                    <input type="hidden" name="_wpcf7_version" value="5.3.2">
                                    <input type="hidden" name="_wpcf7_locale" value="pl_PL">
                                    <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f2272-o1">
                                    <input type="hidden" name="_wpcf7_container_post" value="0">
                                    <input type="hidden" name="_wpcf7_posted_data_hash" value="">
                                    <input type="hidden" name="_wpcf7_recaptcha_response" value="">
                                </div>
                                <div style="width:25%; float:left; min-height:1px;">
                                </div>
                                <div style="width:50%; float:left; min-height:1px;">
                                <h3 style="color:#fff!important; text-align:center;">BĄDŹ NA BIEŻĄCO – zapisz się do newslettera
                                </h3>
                                <p><label>     <span class="wpcf7-form-control-wrap your-email"><input type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="Adres email"></span> </label></p>
                                <div style="max-height: 25px; width:100%; float:left; color:#fff; padding:0px 0 8px 0; font-size:11px; line-height:12px; text-align:justify; font-weight:300; font-family:Arial; margin-bottom:0"><span class="wpcf7-form-control-wrap acceptance-149"><span class="wpcf7-form-control wpcf7-acceptance"><span class="wpcf7-list-item"><input type="checkbox" name="acceptance-149" value="1" aria-invalid="false" checked="checked"></span></span></span> Wyrażam zgodę na informowanie o wydarzeniach organizowanych przez Innovative Group sp. z o.o.</div>
                                <div style="max-height: 25px; width:100%; float:left; color:#fff; padding:8px 0; font-size:11px; line-height:12px; text-align:justify; font-weight:300; font-family:Arial; margin-bottom:0"><span class="wpcf7-form-control-wrap acceptance-155"><span class="wpcf7-form-control wpcf7-acceptance"><span class="wpcf7-list-item"><input type="checkbox" name="acceptance-155" value="1" aria-invalid="false" checked="checked"></span></span></span> Oświadczam, że zapoznałem się z <a style="color:#fff;" target="_blank" href="/polityka-prywatnosci">polityką prywatności</a> i akceptuję jej treść.</div>
                                <div style="width:100%; float:left; color:#fff; padding:8px 0; font-size:11px; line-height:12px; text-align:justify; font-weight:300; font-family:Arial">Administratorem danych jest Innovative Group sp. z o.o. z siedzibą w Warszawie, ul. Ryżowa 49. Dane przetwarzane są w celu zgodnym z treścią udzielonej zgody, do czasu jej cofnięcia. Innovative Group sp. z o.o. ma prawo przetwarzać informacje o tym, że udzieliłaś/eś zgody oraz kiedy została cofnięta, przez okres przewidziany przepisami prawa, na potrzeby rozstrzygania ewentualnych sporów i dochodzenia roszczeń. Masz prawo cofnąć zgodę w każdym czasie, dostępu do danych, sprostowania, usunięcia lub ograniczenia przetwarzania. Masz prawo sprzeciwu, prawo wniesienia skargi do organu nadzorczego lub przeniesienia danych. Informacje handlowe (oferty oraz informacje o usługach) będą przesyłane przy użyciu telefonu lub poczty e-mail, do czasu cofnięcia zgody.<p></p>
                                </div>
                                <p><input type="submit" value="ZAPISUJĘ SIĘ" class="wpcf7-form-control wpcf7-submit"><span class="ajax-loader"></span>
                                </p></div>
                                <div style="width:25%; float:left; min-height:1px;"></div>
                                <div class="wpcf7-response-output" aria-hidden="true"></div><input type="hidden" name="emQr-v" value="CYPg2T"><input type="hidden" name="ebNkiXHzIn" value="1BKXVQFMI6">
                            </form>
                        </div>
                    
                       


                    </div>
                   
                    <!-- Widget#1 -->
                    
     
                    <!-- Widget#1 -->
            </div>

            <div class="footer">
            <img src="https://kongrestransformacji.pl/wp-content/uploads/2019/04/cyfrowa_transformacja_logo-1.png">
        </div>	

        </div>
		<!-- EOF Widgets -->
	
        
        <div class="jx-ievent-post-footer">        
        	<div class="container">              
             <?php if($ievent_data['checkbox_social_footer']): ?>
            <div class="jx-ievent-footer-social">
                <ul>
                    <?php if($ievent_data['text_facebook']): ?>
                    <li><a href="https://www.facebook.com/cryptofuturepl/"><i class="fa fa-facebook"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_twitter']): ?>
                    <li><a href="http://www.twitter.com/<?php echo esc_attr($ievent_data['text_twitter']); ?>"><i class="fa fa-twitter"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_youtube']): ?>
                    <li><a href="https://www.youtube.com/channel/UCnRS2nrazK7DS29ihs7PRaw"><i class="fa fa-youtube"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_googleplus']): ?>
                    <li><a href="http://www.googleplus.com/<?php echo esc_attr($ievent_data['text_googleplus']); ?>"><i class="fa fa-google-plus"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_dribbble']): ?>
                    <li><a href="http://www.dribbble.com/<?php echo esc_attr($ievent_data['text_dribbble']); ?>"><i class="fa fa-dribbble"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_instagram']): ?>
                    <li><a href="http://www.instagram.com/<?php echo esc_attr($ievent_data['text_instagram']); ?>"><i class="fa fa-instagram"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_pinterest']): ?>
                    <li><a href="http://www.pinterest.com/<?php echo esc_attr($ievent_data['text_pinterest']); ?>"><i class="fa fa-pinterest"></i></a></li>
                    <?php endif; ?>
                    <?php if($ievent_data['text_flickr']): ?>
                    <li><a href="http://www.flickr.com/<?php echo esc_attr($ievent_data['text_flickr']); ?>"><i class="fa fa-flickr"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
            <div class="jx-ievent-footer-copyright">
                <?php echo sprintf(esc_html__('%s', 'ievent'),$ievent_data['copyright']); ?> <a href="<?php get_site_url(); ?>"><?php bloginfo('name'); ?></a>
            </div>
        	</div>
        </div>  
        <!-- EOF Social -->
    </footer>
