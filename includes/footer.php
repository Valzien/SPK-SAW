    </div><!-- /page-content -->
</div><!-- /main-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>assets/js/main.js"></script>
<?php if (!empty($extraScript)) echo $extraScript; ?>
</body>
</html>
