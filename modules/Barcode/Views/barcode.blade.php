<svg class="barcode"
    jsbarcode-value="{{ $value }}">
</svg>

<script src="/js/JsBarcode.all.min.js"></script>
<script>
    JsBarcode(".barcode").init();
</script>
