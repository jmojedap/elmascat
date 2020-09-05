<?php 
    $imagenes = $this->App_model->imagenes_carrusel();
?>

<div class="hidden-sm hidden-xs">
    <div id="magik-slideshow" class="magik-slideshow" style="margin: 0px auto;">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12">
                <div id='rev_slider_4_wrapper' class='rev_slider_wrapper fullwidthbanner-container' >
                    <div id='rev_slider_4' class='rev_slider fullwidthabanner'>
                        <ul>
                            <?php foreach ($imagenes->result() as $row_imagen) { ?>
                                <?php 
                                    if (strlen($row_imagen->link)) 
                                    {
                                        $link = $this->Pcrn->preparar_url($row_imagen->link);
                                    }
                                ?>
                                <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb='images/slider_img_2.jpg'>
                                    <img src='<?= $row_imagen->src ?>' data-bgposition='left top' data-bgfit='cover' data-bgrepeat='no-repeat' alt="banner"/>
                                    <div class='tp-caption ExtraLargeTitle sft tp-resizeme ' data-x='-60' data-y='30' data-endspeed='500' data-speed='500' data-start='1100' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'>
                                        <?= $row_imagen->subtitulo ?>
                                    </div>
                                    <div class='tp-caption LargeTitle sfl tp-resizeme ' data-x='-60' data-y='70' data-endspeed='500' data-speed='500' data-start='1300' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'>
                                        <?= $row_imagen->titulo ?>
                                    </div>
                                    <div class='tp-caption sfb tp-resizeme ' data-x='-60' data-y='360' data-endspeed='500' data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>
                                        <a href='<?= $link ?>' class="view-more">
                                            Ver más
                                        </a> 
                                    </div>
                                    <div class='tp-caption Title sft tp-resizeme ' data-x='-60' data-y='130' data-endspeed='500' data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;'>
                                        <?= $row_imagen->descripcion ?>
                                    </div>
                                    <div class='hidden tp-caption Title sft  tp-resizeme ' data-x='0'  data-y='400'  data-endspeed='500'  data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' style='z-index:4; white-space:nowrap;font-size:11px'>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="tp-bannertimer"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#rev_slider_4').show().revolution({
                dottedOverlay: 'none',
                delay: 5000,
                startwidth: 585,
                startheight: 460,
                hideThumbs: 200,
                thumbWidth: 200,
                thumbHeight: 50,
                thumbAmount: 2,
                navigationType: 'thumb',
                navigationArrows: 'solo',
                navigationStyle: 'round',
                touchenabled: 'on',
                onHoverStop: 'on',
                swipe_velocity: 0.7,
                swipe_min_touches: 1,
                swipe_max_touches: 1,
                drag_block_vertical: false,
                spinner: 'spinner0',
                keyboardNavigation: 'off',
                navigationHAlign: 'center',
                navigationVAlign: 'bottom',
                navigationHOffset: 0,
                navigationVOffset: 20,
                soloArrowLeftHalign: 'left',
                soloArrowLeftValign: 'center',
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,
                soloArrowRightHalign: 'right',
                soloArrowRightValign: 'center',
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,
                shadow: 0,
                fullWidth: 'on',
                fullScreen: 'off',
                stopLoop: 'off',
                stopAfterLoops: -1,
                stopAtSlide: -1,
                shuffle: 'off',
                autoHeight: 'off',
                forceFullWidth: 'on',
                fullScreenAlignForce: 'off',
                minFullScreenHeight: 0,
                hideNavDelayOnMobile: 1500,
                hideThumbsOnMobile: 'off',
                hideBulletsOnMobile: 'off',
                hideArrowsOnMobile: 'off',
                hideThumbsUnderResolution: 0,
                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0,
                fullScreenOffsetContainer: ''
            });
        });
</script> 