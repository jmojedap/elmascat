<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $head_title ?></title>

    <link rel="icon" type="image/png" href="<?php echo URL_IMG ?>app/icono.png"/>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Bree+Serif&display=swap');
        body{
            
            padding: 20px 0px;
            min-height: 100vh;
            background: rgb(163,225,255);
            background: -moz-linear-gradient(180deg, rgba(163,225,255,1) 0%, rgba(45,153,205,1) 100%);
            background: -webkit-linear-gradient(180deg, rgba(163,225,255,1) 0%, rgba(45,153,205,1) 100%);
            background: linear-gradient(180deg, rgba(163,225,255,1) 0%, rgba(45,153,205,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#a3e1ff",endColorstr="#2d99cd",GradientType=1);
        }

        .mw800p{
            margin: 0px auto;
            max-width: 800px;
        }

        .w120p{
            width: 120px;
        }

        .page{
            width: 100%;
            margin: 0px auto;
            border: 1px solid #999;
            border-radius: 3px;
            -webkit-box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
            -moz-box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
            box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
        }

        .book_index{
            max-width: 600px;
            margin: 0px auto;
            background-color: #fffde7;
            padding: 1em;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            -webkit-box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
            -moz-box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
            box-shadow: 5px 5px 15px 2px rgba(38,108,140,1);
        }

        .book_index a{
            font-family: 'Bree Serif', serif;
            padding-left: 1em;
            display: block;
            width: 100%;
            text-decoration: none;
            line-height: 2em;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }

        .book_index a.level_1{
            color: #444;
            background-color: #fff9c4;
        }
        .book_index a.level_2{
            padding-left: 2em;
            color: #4e342e;
        }

        .book_index a.level_3{
            padding-left: 3em;
            color: #5d4037;
        }

        .book_index a.active{
            background-color: red;
        }

        .level_1 .index_title { font-size: 1.1em; }
        .level_3 .index_title { font-size: 0.9em; }

        .book_index a:hover{
            background-color: #ffee58;
        }

        .book_index a .num_page{
            width: 2.5em;
            height: 1.8em;
            float: right;
            text-align: center;
            color: white;
            background-color: #f57f17;
            line-height: 2em;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            margin-top: 2px;
        }

        a.page_mini {
            display: block-inline;
        }

        a.page_mini img{
            margin: 0 5px 5px 0;
            border: 1px solid #DDD;
        }

        a.page_mini img:hover{ border-color: #FFF;}
    </style>