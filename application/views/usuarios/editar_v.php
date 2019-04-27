<?php $this->load->view('assets/grocery_crud'); ?>

<script>
    //Rangos de años para fecha
    $(document).ready(function()
    {
        $( ".datepicker-input" ).datepicker( "option", "yearRange", "-99:-14" );
        $( ".datepicker-input" ).datepicker( "option", "maxDate", "+0m +0d" );
    });
</script>

<div class="sep2">
    <?= $output ?>
</div>

