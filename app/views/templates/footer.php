<script src="<?= BASEURL; ?>vendor/jquery-3.7.1.min.js"></script>
<script src="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/semantic.js"></script>
<script src="<?= BASEURL; ?>js/accounting.js"></script>
<script src="<?= BASEURL; ?>js/mathbiila.min.js"></script>
<script src="<?= BASEURL; ?>vendor/node_modules/crypto-js/crypto-js.js"></script>
<script src="<?= BASEURL; ?>js/Encryption.js"></script>

<script type="text/javascript">
    const halamanDefault = '<?php echo $data['key_encrypt'] ?>';
    const halamandok = '<?php echo $data['dok'] ?>';
</script>
<script>
    const BASEURL = '<?= BASEURL; ?>';
</script>

<?php 
if ($data['js'] != '') {
    echo "<script src=" . BASEURL . $data['js'] . "></script>";
}
// if (!empty($data['tambahan_js'])) {
//     echo "<script src=" . BASEURL . $data['tambahan_js'] . "></script>";
// } 
?>
<script nomodule>
    console.info(`Your browser doesn't support native JavaScript modules.`);
</script>
<noscript>
    JAVASCRIPT BROWSER NON AKTIF ! AKTIFKAN JAVASCRIPT UNTUK MENGGUNAKAN APLIKASI
</noscript>
</body>

</html>