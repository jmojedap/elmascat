<link href="<?= base_url() ?>assets/bootstrap_datepicker/css/bootstrap-datepicker.css" rel='stylesheet' />
<script src="<?= base_url() ?>assets/bootstrap_datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/bootstrap_datepicker/locales/bootstrap-datepicker.es.min.js"></script>

<script>
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('.bs_datepicker').datepicker({
            format: "yyyy-mm-dd",
            daysOfWeekHighlighted: "0,6",
            language: "es",
            weekStart: 7
        });
        
    });
</script>