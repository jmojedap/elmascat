<?php
    $carpeta_ejemplo = URL_ASSETS . 'polo/blue/';
?>

<footer class="footer wow bounceInUp animated">
    <div class="brand-logo hidden">
        <div class="container">
            <div class="slider-items-products">
                <div id="brand-logo-slider" class="product-flexslider hidden-buttons">
                    <div class="slider-items slider-width-col6"> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo1.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo2.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo3.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo4.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo5.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo6.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo1.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                        <!-- Item -->
                        <div class="item"> <a href="#x"><img src="<?= $carpeta_ejemplo ?>images/b-logo4.png" alt="Image"></a> </div>
                        <!-- End Item --> 

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-7">
                    <div class="block-subscribe">
                        <div class="newsletter hidden">
                            <form>
                                <h4>newsletter</h4>
                                <input type="text" placeholder="Enter your email address" class="input-text required-entry validate-email" title="Sign up for our newsletter" id="newsletter1" name="email">
                                <button class="subscribe" title="Subscribe" type="submit"><span>Subscribe</span></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="social">
                        <ul>
                            <li class="">
                                <a href="https://www.facebook.com/districatolicas/" target="_blank">
                                    <img src="<?= URL_IMG ?>app/facebook.png" alt="Logo Facebook" class="logo_social">
                                </a>
                            </li>
                            <li class="">
                                <a href="https://www.instagram.com/districatolicas/" target="_blank">
                                    <img src="<?= URL_IMG ?>app/instagram.png" alt="Logo Instagram" class="logo_social">
                                </a>
                            </li>
                            <li class="">
                                <a href="https://wa.me/573013054053" target="_blank">
                                    <img src="<?= URL_IMG ?>app/whatsapp_logo.png" alt="Logo WhatsApp" class="logo_social">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-middle container">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="footer-logo">
                    <a href="<?= base_url() . 'posts/leer/15/sobre_nosotros' ?>" title="Districatólicas Unidas S.A.S.">
                        <img src="<?= URL_IMG ?>app/logo_polo.png" alt="logo">
                    </a>
                </div>
                <p>Realice sus pagos seguros mediante </p>
                <div class="payment-accept_">
                    <div style="max-width: 250px;">
                        <img width="100%" src="<?= URL_IMG ?>app/pagosonline.png" alt="payment"> 
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-4">
                <h4>Servicios</h4>
                <ul class="links">
                    <li class="first">
                        <a href="<?= base_url() . 'posts/leer/15/sobre_nosotros' ?>">Contáctenos</a>
                    </li>
                    <li class="last">
                        <?= anchor("pedidos/estado", "Estado de pedido", 'class="" title=""') ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 col-sm-4">
                <h4>Sitios de interés</h4>
                <ul class="links">
                    <li class="first">
                        <?= anchor('http://www.minutosdeamor.com', 'Minutos de Amor', 'target="_blank"') ?>
                    </li>
                    <li>
                        <?= anchor('http://www.cursilloscolombia.org/', 'Movimiento Cursillos de Cristiandad', 'target="_blank"') ?>
                    </li>
                    <li class="last">
                        <?= anchor('http://www.radiomariacol.org/', 'Radio María', 'target="_blank"') ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 col-sm-4">
                <h4>Información</h4>
                <ul class="links">
                    <li class="first">
                        <?= anchor("posts/leer/17/terminos-de-uso", 'Términos de uso') ?>
                    </li>
                    <li class=" last">
                        <?= anchor("posts/leer/16/politica-de-privacidad", 'Política de privacidad') ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-4">
                <h4>Contáctenos</h4>
                <div class="contacts-info">
                    <address>
                        <i class="add-icon">&nbsp;</i>Av. Ciudad de Cali No. 72A-41<br>
                        &nbsp;Bogotá D.C. Colombia
                    </address>
                    <div class="phone-footer"><i class="phone-icon">&nbsp;</i> +57 1 745 6922</div>
                    <div class="email-footer"><i class="email-icon">&nbsp;</i> <a href="#">info@districatolicas.com</a> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-xs-12 coppyright"> &copy; 2018 | Desarrollado por <a href="http://www.pacarina.com" target="_blank">Pacarina.com</a> </div>
                <div class="col-sm-7 col-xs-12 company-links">
                    
                </div>
            </div>
        </div>
    </div>
</footer>