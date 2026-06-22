<?php
/** Подключение JS + данные для клиентского поиска. */
?>
<script>window.__SEARCH_INDEX__ = <?= json_encode(search_index(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script src="<?= asset('js/main.js') ?>" defer></script>
