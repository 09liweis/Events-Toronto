    </body>
        <script type="text/javascript">
            var currentLocation = <?=json_encode($currentLocation)?>;
        </script>
        <?php foreach ($scripts as $script) { ?>
        <script type="text/javascript" src="<?=$script?>"></script>
        <?php } ?>
</html>