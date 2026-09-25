/* global jQuery */
(function ($) {
    'use strict';

    function SortableControl($el) {
        this.$list     = $el.find('.social-items');
        this.$textarea = $el.find('.social-json-input');
        this._bind();
        this._syncTextarea();  // estado inicial garantizado
        this._updateArrows();
    }

    SortableControl.prototype._bind = function () {
        var self = this;

        this.$list.on('click', '.social-move-up', function () {
            var $item = $(this).closest('.social-item');
            var $prev = $item.prev('.social-item');
            if ($prev.length) {
                $item.insertBefore($prev);
                self._syncAndUpdate();
            }
        });

        this.$list.on('click', '.social-move-down', function () {
            var $item = $(this).closest('.social-item');
            var $next = $item.next('.social-item');
            if ($next.length) {
                $item.insertAfter($next);
                self._syncAndUpdate();
            }
        });

        this.$list.on('input', '.social-item__title-input, .social-item__url-input', function () {
            self._syncTextarea();
        });

        // Sincronización final justo antes de enviar el formulario
        this.$textarea.closest('form').on('submit', function () {
            self._syncTextarea();
        });
    };

    SortableControl.prototype._syncAndUpdate = function () {
        this._syncTextarea();
        this._updateArrows();
    };

    SortableControl.prototype._syncTextarea = function () {
        var data = [];
        this.$list.find('.social-item').each(function () {
            data.push({
                key:   $(this).data('key'),
                title: $(this).find('.social-item__title-input').val(),
                url:   $(this).find('.social-item__url-input').val()
            });
        });
        this.$textarea.val(JSON.stringify(data)).trigger('change');
    };

    SortableControl.prototype._updateArrows = function () {
        var $items = this.$list.find('.social-item');
        var total  = $items.length;
        $items.each(function (i) {
            $(this).find('.social-move-up').prop('disabled', i === 0);
            $(this).find('.social-move-down').prop('disabled', i === total - 1);
        });
    };

    $(document).ready(function () {
        $('.misiones2027-social-control, .misiones2027-contact-control').each(function () {
            new SortableControl($(this));
        });
    });

}(jQuery));
