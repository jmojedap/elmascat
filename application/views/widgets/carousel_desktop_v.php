<div class="mb-2 only-lg">
    <div class="rev_slider_wrapper fullwidthbanner-container">
        <div id="rev_slider_desktop" class="rev_slider fullwidthabanner">
            <ul>
                <?php foreach ($carousel_desktop_images->result() as $image) { ?>
                    <li data-transition="random" data-slotamount="7" data-masterspeed="1000" data-thumb="images/slider_img_2.jpg">
                        <img src="<?= $image->url ?>" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" alt="banner">
                        <div class="tp-caption ExtraLargeTitle sft tp-resizeme " data-x="-60" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:2; white-space:nowrap;">
                            <?= $image->subtitle ?>
                        </div>
                        <div class="tp-caption LargeTitle sfl tp-resizeme" data-x="-60" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:3; white-space:nowrap;">
                            <?= $image->title ?>
                        </div>
                        <?php if ( strlen($image->external_link) > 0) : ?>
                            <div class="tp-caption sfb tp-resizeme " data-x="-60" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:4; white-space:nowrap;">
                                <a href="<?= $image->external_link ?>" class="view-more">Ver más</a> 
                            </div>
                        <?php endif; ?>
                        <div class="tp-caption Title sft tp-resizeme " data-x="-60" data-y="130" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:4; white-space:nowrap;">
                            <?= $image->description ?>
                        </div>
                <?php } ?>
            </ul>
            <div class="tp-bannertimer"></div>
        </div>
    </div>
</div>