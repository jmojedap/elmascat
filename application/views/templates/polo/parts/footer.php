<?php
    $carpeta_ejemplo = URL_ASSETS . 'polo/blue/';
?>

<footer class="footer wow bounceInUp animated">
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
                            <li>
                                <a href="https://www.facebook.com/districatolicas/" target="_blank">
                                    <i class="fab fa-facebook fa-3x" style="color: white;"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/districatolicas/" target="_blank">
                                    <i class="fab fa-instagram fa-3x" style="color: white;"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://wa.me/573013054053" target="_blank">
                                    <i class="fab fa-whatsapp fa-3x" style="color: white;"></i>
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
            <div class="col-md-5 col-sm-4">
                <div class="footer-logo">
                    <a href="<?= base_url() . 'posts/leer/15/sobre_nosotros' ?>" title="Districatólicas Unidas S.A.S.">
                        <img src="<?= URL_IMG ?>app/logo_polo.png" alt="logo">
                    </a>
                </div>
                <div class="payment-accept_">
                    <div style="max-width: 302px;">
                        <img width="100%" src="<?= URL_IMG ?>app/payu_medios_pago.png" alt="payment" class="mb-2"> 
                        <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
                    </div>
                </div>
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
                    <li class="last">
                        <?= anchor("pedidos/estado", "Estado de pedido", 'class="" title=""') ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-4">
                <h4>Contáctenos</h4>
                <div class="contacts-info">
                    <div class="row">
                        <div class="col col-sm-2">
                            <i class="add-icon fas fa-map-marker-alt">&nbsp;</i>
                        </div>
                        <div class="col col-sm-10">
                            <address style="margin-top: 0px;">
                                Av Ciudad de Cali No. 72A-41
                                <br>
                                Bogotá D.C. - Colombia
                            </address>
                        </div>
                    </div>
                    <div class="phone-footer"><i class="phone-icon fas fa-phone">&nbsp;</i>301 305 4053</div>
                    <div class="email-footer"><i class="email-icon">&nbsp;</i> <a href="#">info@districatolicas.com</a> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center">
        <div class="coppyright"> &copy; 2022 &middot; Desarrollado por <a href="http://www.pacarina.com" target="_blank">Pacarina Media Lab</a> </div>
    </div>
</footer>