/**
 * KWL Resume Theme — Admin JavaScript
 * Handles repeater fields, sortable drag-to-reorder, skills live preview,
 * and custom sections builder.
 */
(function ($) {
    'use strict';

    /* ────────────────────────────────────────
       UTILITY: Clone a template and append
    ──────────────────────────────────────── */
    function cloneTemplate(templateId) {
        var tpl = document.getElementById(templateId);
        if (!tpl) return null;
        var div = document.createElement('div');
        div.innerHTML = tpl.innerHTML.trim();
        return div.firstChild;
    }

    /* ────────────────────────────────────────
       GENERIC REPEATER — Add button
       data-target: list container ID
       data-template: <script type="text/html"> ID
    ──────────────────────────────────────── */
    $(document).on('click', '.kwl-add-repeater, #kwl-add-custom-link', function () {
        var templateId = $(this).data('template');
        var targetId   = $(this).data('target');
        var node = cloneTemplate(templateId);
        if (node && targetId) {
            $('#' + targetId).append(node);
        }
    });

    /* ────────────────────────────────────────
       GENERIC REPEATER — Remove button
    ──────────────────────────────────────── */
    $(document).on('click', '.kwl-remove-item', function () {
        if (!confirm(kwlResumeAdmin.confirmDelete)) return;
        $(this).closest('.kwl-repeater-item').remove();
    });

    /* ────────────────────────────────────────
       SORTABLE lists
    ──────────────────────────────────────── */
    $('.kwl-sortable').sortable({
        handle:      '.kwl-repeater-handle',
        placeholder: 'kwl-sortable-placeholder',
        tolerance:   'pointer',
        start: function (e, ui) {
            ui.item.addClass('kwl-sortable-dragging');
            ui.placeholder.height(ui.item.outerHeight());
        },
        stop: function (e, ui) {
            ui.item.removeClass('kwl-sortable-dragging');
        }
    });

    /* ────────────────────────────────────────
       SKILLS LIVE PREVIEW
    ──────────────────────────────────────── */
    var $skillsTextarea = $('textarea[name="kwl_skills_raw"]');
    var $skillsPreview  = $('#kwl-skills-preview');

    if ($skillsTextarea.length && $skillsPreview.length) {
        $skillsTextarea.on('input', function () {
            var raw    = $(this).val();
            var skills = raw.split(',').map(function (s) { return s.trim(); }).filter(Boolean);
            var html   = '';
            skills.forEach(function (skill) {
                html += '<span class="kwl-skill-preview-tag">' + $('<div>').text(skill).html() + '</span>';
            });
            $skillsPreview.html(html || '<em style="color:#5a7080;font-size:0.75rem">Skills will appear here…</em>');
        });
    }

    /* ────────────────────────────────────────
       CUSTOM SECTIONS — Add a new section
    ──────────────────────────────────────── */
    var customSectionCount = $('.kwl-custom-section').length;

    $('#kwl-add-custom-section').on('click', function () {
        var si  = customSectionCount++;
        var html = buildCustomSectionHTML(si);
        $('#kwl-custom-sections-list').append(html);
    });

    function buildCustomSectionHTML(si) {
        return [
            '<div class="kwl-custom-section" data-section-index="' + si + '">',
            '  <div class="kwl-custom-section-header">',
            '    <div class="kwl-field-row kwl-field-row--inline" style="flex:1;margin:0">',
            '      <div style="flex:1">',
            '        <label>' + escHtml(kwlResumeAdmin.labelSectionTitle || 'Section Title') + '</label>',
            '        <input type="text" name="kwl_cs_title[]" class="large-text" placeholder="e.g. Awards, Volunteer Work…">',
            '      </div>',
            '      <div style="align-self:flex-end;padding-bottom:4px">',
            '        <label>',
            '          <input type="checkbox" name="kwl_cs_enabled[' + si + ']" value="1" checked>',
            '          ' + escHtml(kwlResumeAdmin.labelEnabled || 'Enabled'),
            '        </label>',
            '      </div>',
            '    </div>',
            '    <button type="button" class="kwl-remove-section button-link-delete">' + escHtml(kwlResumeAdmin.labelRemoveSection || '✕ Remove Section') + '</button>',
            '  </div>',
            '  <div class="kwl-custom-entries" id="kwl-cs-entries-' + si + '">',
            '  </div>',
            '  <button type="button" class="button kwl-add-cs-entry" data-section="' + si + '">+ ' + escHtml(kwlResumeAdmin.labelAddEntry || 'Add Entry') + '</button>',
            '</div>'
        ].join('\n');
    }

    /* ────────────────────────────────────────
       CUSTOM SECTIONS — Add entry to a section
    ──────────────────────────────────────── */
    $(document).on('click', '.kwl-add-cs-entry', function () {
        var si   = $(this).data('section');
        var html = buildCustomEntryHTML(si);
        $('#kwl-cs-entries-' + si).append(html);
    });

    function buildCustomEntryHTML(si) {
        return [
            '<div class="kwl-repeater-item kwl-cs-entry">',
            '  <div class="kwl-repeater-body">',
            '    <div class="kwl-field-row kwl-field-row--inline">',
            '      <div>',
            '        <label>Heading</label>',
            '        <input type="text" name="kwl_cs_entry_heading[' + si + '][]" class="large-text">',
            '      </div>',
            '      <div>',
            '        <label>Subheading</label>',
            '        <input type="text" name="kwl_cs_entry_sub[' + si + '][]" class="regular-text">',
            '      </div>',
            '      <div>',
            '        <label>Date</label>',
            '        <input type="text" name="kwl_cs_entry_date[' + si + '][]" class="regular-text">',
            '      </div>',
            '    </div>',
            '    <div class="kwl-field-row">',
            '      <label>Description</label>',
            '      <textarea name="kwl_cs_entry_desc[' + si + '][]" rows="2" class="large-text"></textarea>',
            '    </div>',
            '    <div class="kwl-field-row">',
            '      <label>Bullet Points (one per line)</label>',
            '      <textarea name="kwl_cs_entry_bullets[' + si + '][]" rows="3" class="large-text"></textarea>',
            '    </div>',
            '  </div>',
            '  <button type="button" class="kwl-remove-item button-link-delete">✕ Remove Entry</button>',
            '</div>'
        ].join('\n');
    }

    /* ────────────────────────────────────────
       CUSTOM SECTIONS — Remove a whole section
    ──────────────────────────────────────── */
    $(document).on('click', '.kwl-remove-section', function () {
        if (!confirm(kwlResumeAdmin.confirmDelete)) return;
        $(this).closest('.kwl-custom-section').remove();
    });

    /* ────────────────────────────────────────
       UTILITY: safe HTML escape
    ──────────────────────────────────────── */
    function escHtml(str) {
        return $('<div>').text(str || '').html();
    }

    /* ────────────────────────────────────────
       SORTABLE placeholder CSS (injected)
    ──────────────────────────────────────── */
    $('<style>.kwl-sortable-placeholder { background: #e0f2f1; border: 2px dashed #0D9488; border-radius: 6px; visibility: visible !important; }</style>').appendTo('head');

}(jQuery));
