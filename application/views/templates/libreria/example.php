<?php
    $imagenes_carrusel = $this->App_model->imagenes_carrusel();
?>

<div class="row">
    <div class="col-md-3">
        Categorías
    </div>
    <div class="col-md-9">
        <div class="rev_slider_wrapper fullwidthbanner-container">
            <div id="rev_slider" class="rev_slider fullwidthabanner">
                <ul>
                    <?php foreach ($imagenes_carrusel->result() as $row_imagen) { ?>
                        <?php 
                            if (strlen($row_imagen->link)) 
                            {
                                $link = $this->Pcrn->preparar_url($row_imagen->link);
                            }
                        ?>
                        <li data-transition="random" data-slotamount="7" data-masterspeed="1000" data-thumb="images/slider_img_2.jpg">
                            <img src="<?= $row_imagen->src ?>" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" alt="banner"/>
                            <div class="tp-caption ExtraLargeTitle sft tp-resizeme " data-x="-60" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:2; white-space:nowrap;">
                                <?= $row_imagen->subtitulo ?>
                            </div>
                            <div class="tp-caption LargeTitle sfl tp-resizeme" data-x="-60" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:3; white-space:nowrap;">
                                <?= $row_imagen->titulo ?>
                            </div>
                            <div class="tp-caption sfb tp-resizeme " data-x="-60" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:4; white-space:nowrap;">
                                <a href="<?= $link ?>" class="view-more">Ver más</a> 
                            </div>
                            <div class="tp-caption Title sft tp-resizeme " data-x="-60" data-y="130" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:4; white-space:nowrap;">
                                <?= $row_imagen->descripcion ?>
                            </div>
                            <div class="d-none tp-caption Title sft  tp-resizeme " data-x="0"  data-y="400"  data-endspeed="500"  data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" style="z-index:4; white-space:nowrap;font-size:11px">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tp-bannertimer"></div>
            </div>
        </div>
    </div>
</div>
