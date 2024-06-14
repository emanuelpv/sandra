<?php


function alerta($mensagem = null, $tipo = null, $tempo = null)
{
    if ($tempo == null) {
        $tempo = 5000;
    } else {
        $tempo = $tempo;
    }
?>
    <script src="<?php echo base_url() ?>/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        Swal.fire({
            position: 'bottom-end',
            icon: '<?php echo $tipo ?>',
            title: '<?php echo $mensagem ?>',
            showConfirmButton: false,
            timer: '<?php echo $tempo ?>',
        })
    </script>

<?php
}


function ajuda($mensagem, $tipo)
{

?>




<?php
}




?>