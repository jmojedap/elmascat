<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<style>
body {
  padding: 30px 0;
}

/*!*
* Slick Custom Theme
*/
.slick-carousel .slick-item {
  color: white;
  background-color: #3498db;
  min-height: 150px;
  position: relative;
  text-align: center;
  text-transform: uppercase;
  margin: 0 15px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.slick-carousel .slick-item:hover {
  opacity: .7
}

.slick-prev:before,
.slick-next:before {
  color: black;
  content: '';
}


/**!
* Caption Styles
*/

.caption-item {  
  right: 0;
  margin: 0px;
  padding: 0px;
}

.caption-link {
  display: block;
  position: relative;
  margin: 0 auto;
  max-width: 400px;
}

.caption-link .caption-layer {
  position: absolute;
  width: 100%;
  height: 100%;
  opacity: 0;
  -webkit-transition: all ease .5s;
  -moz-transition: all ease .5s;
  transition: all ease .5s;
}

.caption-layer.green {
  background: rgba(173, 219, 50, .5);
}

.caption-layer.light-green {
  background: rgba(6, 209, 164, .7);
}

.caption-layer.blue {
  background: rgba(152, 231, 254, .5);
}

.caption-link .caption-layer:hover,
.caption-layer.video-icon {
  opacity: 1;
}

.caption-link .caption-layer .caption-content {
  position: absolute;
  top: 35%;
  width: 100%;
  text-align: center;
  font-size: 50px;
  color: #fff;
}

.caption-link .caption-layer .caption-content p {
  display: none;
}
</style>

<script>
    $(document).ready(function(){
        $('.slick-carousel').slick({
            slidesToShow: 7,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1500,
        });
    });
</script>

<div class="slick-carousel">
  <div class="slick-item">LIBRO 1</div>
  <div class="slick-item">LIBRO 2</div>
  <div class="slick-item">LIBRO 3</div>
  <div class="slick-item">LIBRO 4</div>
  <div class="slick-item">LIBRO 5</div>
  <div class="slick-item">LIBRO 6</div>
  <div class="slick-item">LIBRO 7</div>
  <div class="slick-item">LIBRO 1</div>
  <div class="slick-item">LIBRO 2</div>
  <div class="slick-item">LIBRO 3</div>
  <div class="slick-item">LIBRO 4</div>
  <div class="slick-item">LIBRO 5</div>
  <div class="slick-item">LIBRO 6</div>
  <div class="slick-item">LIBRO 7</div>
  <div class="slick-item">LIBRO 1</div>
  <div class="slick-item">LIBRO 2</div>
  <div class="slick-item">LIBRO 3</div>
  <div class="slick-item">LIBRO 4</div>
  <div class="slick-item">LIBRO 5</div>
  <div class="slick-item">LIBRO 6</div>
  <div class="slick-item">LIBRO 7</div>
  <div class="slick-item">LIBRO 1</div>
  <div class="slick-item">LIBRO 2</div>
  <div class="slick-item">LIBRO 3</div>
  <div class="slick-item">LIBRO 4</div>
  <div class="slick-item">LIBRO 5</div>
  <div class="slick-item">LIBRO 6</div>
  <div class="slick-item">LIBRO 7</div>
</div>

