<header>
        <div class="header-1">
        	<?php if ($ievent_data['check_sticky_header']): ?>
        	<div class="jx-ievent-header jx-ievent-sticky">
            <?php else: ?>
            <div class="jx-ievent-header">
            <?php endif; ?>
                        	
            	<div class="container">
                	<div class="sixteen columns">
                        <div class="jx-ievent-logo left"><a href="<?php echo esc_url( home_url() ); ?>">
                        <img src="<?php echo esc_url($ievent_data['logo']); ?>" alt="<?php bloginfo('name'); ?>" class="logo" />
						<?php if($ievent_data['logo_retina'] && $ievent_data['retina_logo_width'] && $ievent_data['retina_logo_height']): ?>
						<?php
                        $pixels ="";
                        if(is_numeric($ievent_data['retina_logo_width']) && is_numeric($ievent_data['retina_logo_height'])):
                        $pixels ="px";
                        endif; ?>
                        <img src="<?php echo esc_url($ievent_data["logo_retina"]); ?>" alt="<?php bloginfo('name'); ?>" class="retina_logo" />
                        <?php endif; ?>
                    	</a>
                    </div>
                    
                    <?php
						
						if ( class_exists( 'WooCommerce' ) ) {
						
							global $woocommerce;
							
							// get cart quantity
							$qty = $woocommerce->cart->get_cart_contents_count();
							
							// get cart total
							$total = $woocommerce->cart->get_cart_total();
							
							// get cart url
							//old code : $cart_url = $woocommerce->cart->get_cart_url();
							$cart_url = $woocommerce->wc_get_cart_url;
						}
						?>
                    
                        <div class="jx-ievent-menu right r">
                            <div id="jx-ievent-main-menu" class="main-menu">                               
                                <ul>
									<li>
										<a href="https://kongrestransformacji.pl/#about">O WYDARZENIU</a>
									</li>
									
									<li>
										<a href="https://kongrestransformacji.pl/#speakers">PRELEGENCI</a>
									</li>
									<li>
										<a href="https://kongrestransformacji.pl/#schedule">AGENDA</a>
									</li>
									<li>
										<a href="https://kongrestransformacji.pl/partnerzy">PARTNERZY</a>
									</li>
									<li>
										<a href="https://kongrestransformacji.pl/kontakt">KONTAKT</a>
									</li>
									<li>
										<a href="">RELACJA</a>
									</li>
									<li class="register"  style="cursor:pointer; ">
										<a>REJESTRACJA</a>
									</li>


								</ul>
                            
                            
                            </div>
                            
                        </div>
                    </div>
                    <!-- EOF columns -->
                </div>
            </div>
            </div>       
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

			
        </header>     
        <!-- EDF Header -->