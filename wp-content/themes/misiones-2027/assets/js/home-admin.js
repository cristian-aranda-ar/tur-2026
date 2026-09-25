/* global jQuery, wp, tcHomeData */
(function ($) {
    'use strict';

    // ── Media uploader: Hero video ──────────────────────────
    var $heroBtn = $('#tc-hero-upload-btn');
    if ($heroBtn.length && typeof wp !== 'undefined' && wp.media) {
        $heroBtn.on('click', function () {
            var frame = wp.media({
                title: 'Seleccionar video',
                button: { text: 'Usar este video' },
                library: { type: 'video' },
                multiple: false
            });
            frame.on('select', function () {
                var att = frame.state().get('selection').first().toJSON();
                $('#hero_video_url').val(att.url);
            });
            frame.open();
        });
    }

    // Clear field buttons
    $(document).on('click', '.tc-clear-field', function () {
        var target = $(this).data('target');
        if (target) $('#' + target).val('');
    });

    // ── Generic sortable helpers ──────────────────────────────
    function syncJson($list, $store, mapFn) {
        var data = [];
        $list.find('.tc-card-item, .tc-exp-item').each(function () {
            data.push(mapFn($(this)));
        });
        $store.val(JSON.stringify(data));
    }

    function updateArrows($list, itemSel) {
        var $items = $list.find(itemSel);
        var total  = $items.length;
        $items.each(function (i) {
            $(this).find('.social-move-up').prop('disabled', i === 0);
            $(this).find('.social-move-down').prop('disabled', i === total - 1);
        });
    }

    function bindSortable($list, itemSel, syncFn) {
        $list.on('click', '.social-move-up', function () {
            var $item = $(this).closest(itemSel);
            var $prev = $item.prev(itemSel);
            if ($prev.length) { $item.insertBefore($prev); syncFn(); updateArrows($list, itemSel); }
        });
        $list.on('click', '.social-move-down', function () {
            var $item = $(this).closest(itemSel);
            var $next = $item.next(itemSel);
            if ($next.length) { $item.insertAfter($next); syncFn(); updateArrows($list, itemSel); }
        });
    }

    // ── Quick Actions ──────────────────────────────────────────
    var $qaWrap = $('.tc-qa-control');
    if ($qaWrap.length) {
        var $qaList  = $qaWrap.find('.tc-card-list');
        var $qaStore = $qaWrap.find('.tc-json-store');

        function syncQa() {
            syncJson($qaList, $qaStore, function ($item) {
                return {
                    icon:  $item.find('.tc-qa-icon').val(),
                    label: $item.find('.tc-qa-label').val(),
                    desc:  $item.find('.tc-qa-desc').val(),
                    href:  $item.find('.tc-qa-href').val()
                };
            });
            // Update header title
            $qaList.find('.tc-card-item').each(function () {
                var lbl = $(this).find('.tc-qa-label').val();
                $(this).find('.tc-card-item__title').text(lbl);
            });
        }

        bindSortable($qaList, '.tc-card-item', syncQa);
        $qaList.on('input', 'input, select', syncQa);
        $qaWrap.closest('form').on('submit', syncQa);
        syncQa();
        updateArrows($qaList, '.tc-card-item');
    }

    // ── Experiencias ──────────────────────────────────────────
    var $expWrap = $('.tc-exp-control');
    if ($expWrap.length) {
        var $expList  = $expWrap.find('.tc-card-list');
        var $expStore = $expWrap.find('.tc-json-store');

        function syncExp() {
            syncJson($expList, $expStore, function ($item) {
                var cats = $item.find('.tc-exp-cats').val();
                return {
                    label:      $item.find('.tc-exp-label').val(),
                    color:      $item.find('.tc-exp-color').val(),
                    desc:       $item.find('.tc-exp-desc').val(),
                    icon:       $item.find('.tc-exp-icon').val(),
                    categorias: cats.split(',').map(function (s) { return s.trim(); }).filter(Boolean)
                };
            });
            $expList.find('.tc-exp-item').each(function () {
                var lbl   = $(this).find('.tc-exp-label').val();
                var color = $(this).find('.tc-exp-color').val();
                $(this).find('.tc-card-item__title').text(lbl);
                $(this).find('.tc-exp-color-dot').css('background', color);
            });
        }

        bindSortable($expList, '.tc-exp-item', syncExp);
        $expList.on('input', 'input', syncExp);
        $expWrap.closest('form').on('submit', syncExp);
        syncExp();
        updateArrows($expList, '.tc-exp-item');
    }

    // ── Destinos picker ───────────────────────────────────────
    var $destPicker = $('.tc-destinos-picker');
    if ($destPicker.length && typeof tcHomeData !== 'undefined') {
        var $search   = $('#tc-destinos-search');
        var $dropdown = $('#tc-destinos-dropdown');
        var $selList  = $('#tc-destinos-selected');
        var $destJson = $('#tc-destinos-json');
        var allPosts  = tcHomeData.ruPosts || [];

        function getSelectedIds() {
            var ids = [];
            $selList.find('.tc-destino-item').each(function () {
                ids.push(parseInt($(this).data('id'), 10));
            });
            return ids;
        }

        function syncDestJson() {
            $destJson.val(JSON.stringify(getSelectedIds()));
        }

        function updateDestArrows() {
            var $items = $selList.find('.tc-destino-item');
            var total  = $items.length;
            $items.each(function (i) {
                $(this).find('.social-move-up').prop('disabled', i === 0);
                $(this).find('.social-move-down').prop('disabled', i === total - 1);
            });
        }

        function addDestItem(id, title) {
            if (getSelectedIds().indexOf(id) !== -1) return;
            var esc = $('<span>').text(title).html();
            var $li = $([
                '<li class="tc-destino-item" data-id="' + id + '">',
                '<span class="tc-destino-item__title">' + esc + '</span>',
                '<div class="social-item__arrows">',
                '<button type="button" class="social-move-up" title="Subir">↑</button>',
                '<button type="button" class="social-move-down" title="Bajar">↓</button>',
                '</div>',
                '<button type="button" class="tc-destino-remove button-link" title="Quitar">✕</button>',
                '</li>'
            ].join(''));
            $selList.append($li);
            syncDestJson();
            updateDestArrows();
        }

        $selList.on('click', '.social-move-up', function () {
            var $item = $(this).closest('.tc-destino-item');
            var $prev = $item.prev('.tc-destino-item');
            if ($prev.length) { $item.insertBefore($prev); syncDestJson(); updateDestArrows(); }
        });
        $selList.on('click', '.social-move-down', function () {
            var $item = $(this).closest('.tc-destino-item');
            var $next = $item.next('.tc-destino-item');
            if ($next.length) { $item.insertAfter($next); syncDestJson(); updateDestArrows(); }
        });
        $selList.on('click', '.tc-destino-remove', function () {
            $(this).closest('.tc-destino-item').remove();
            syncDestJson();
            updateDestArrows();
        });

        $search.on('input', function () {
            var q = $(this).val().toLowerCase().trim();
            if (!q) { $dropdown.hide().empty(); return; }
            var matches = allPosts.filter(function (p) {
                return p.title.toLowerCase().indexOf(q) !== -1;
            }).slice(0, 8);
            if (!matches.length) { $dropdown.hide().empty(); return; }
            $dropdown.empty();
            matches.forEach(function (p) {
                var $li = $('<li role="option" tabindex="0">' + $('<span>').text(p.title).html() + '</li>');
                $li.on('click', function () {
                    addDestItem(p.id, p.title);
                    $search.val('');
                    $dropdown.hide().empty();
                });
                $li.on('keydown', function (e) {
                    if (e.key === 'Enter') { $(this).trigger('click'); }
                });
                $dropdown.append($li);
            });
            $dropdown.show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.tc-search-row').length) $dropdown.hide();
        });

        $destPicker.closest('form').on('submit', syncDestJson);
        syncDestJson();
        updateDestArrows();
    }

}(jQuery));
