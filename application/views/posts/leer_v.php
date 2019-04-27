<style>
    .polo_post {
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        border: 1px solid #EFEFEF;
        padding: 10px 10px 20px 15px;
        margin-top: 10px;
        background-color: #FEFEFE;
    }
    
    .polo_post ul {
        list-style-type: disc;
        padding-left: 20px;
    }
</style>

<div class="row" style="margin-bottom: 30px;">
    <div class="col-md-9">
        <div class="page-title">
            <h2 class="title"><?= $row->nombre_post ?></h2>
        </div>
        
        <div class="polo_post">
            <p class="text-muted"><?= $row->texto_1 ?></p>
            <?=  $row->contenido?>
        </div>
    </div>
    
    <div class="col-md-3">

    </div>
</div>
