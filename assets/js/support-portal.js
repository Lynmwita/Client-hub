/**
 * Jocsoft Client Support & Knowledge Hub JavaScript
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // Tab switching
        $('.tab-btn, .switch-tab-btn').on('click', function () {
            var targetTab = $(this).data('tab') || $(this).data('target');
            if (!targetTab) return;

            $('.tab-btn').removeClass('active');
            $('.tab-btn[data-tab="' + targetTab + '"]').addClass('active');

            $('.tab-pane').hide();
            $('#' + targetTab).fadeIn(150);
        });

        // Submit Support Ticket Form
        $('#jocsoft-ticket-form').on('submit', function (e) {
            e.preventDefault();

            var $btn = $('#submit-ticket-btn');
            $btn.prop('disabled', true).text('Routing Ticket to Support Team...');

            var payload = {
                client_name: $('#ticket_client').val().trim(),
                client_email: $('#ticket_email').val().trim(),
                system: $('#ticket_system').val(),
                urgency: $('#ticket_urgency').val(),
                subject: $('#ticket_subject').val().trim(),
                details: $('#ticket_details').val().trim()
            };

            $.ajax({
                url: jocsoftSupportData.rootUrl + 'tickets/create',
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', jocsoftSupportData.nonce);
                },
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function (res) {
                    $btn.prop('disabled', false).text('Submit Ticket to Engineering Queue');
                    if (res.success) {
                        $('#jocsoft-ticket-form')[0].reset();
                        $('#ticket-feedback').html(
                            '<div style="background:#d1fae5; color:#047857; padding:12px; border-radius:4px; margin-top:16px;">' +
                            '<strong>Ticket Created: ' + res.ticket_ref + '</strong><br>' + res.message +
                            '</div>'
                        ).show();

                        // Add ticket to table
                        var newRow = '<tr>' +
                            '<td><strong>' + res.ticket_ref + '</strong></td>' +
                            '<td>' + payload.subject + '<br><small class="text-muted">' + payload.system + '</small></td>' +
                            '<td><span class="jocsoft-badge urgency-' + payload.urgency.toLowerCase() + '">' + payload.urgency + '</span></td>' +
                            '<td><span class="status-pill status-assigned">Submitted</span></td>' +
                            '<td>Support Queue (Routing)</td>' +
                            '<td>Just now</td>' +
                            '</tr>';
                        $('#tickets-table-body').prepend(newRow);
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).text('Submit Ticket to Engineering Queue');
                    alert('Unable to submit ticket. Please verify all required fields.');
                }
            });
        });

        // Knowledge Base Live Search
        $('#kb-search-btn').on('click', function () {
            performKBSearch();
        });

        $('#kb-search-input').on('keyup', function (e) {
            if (e.key === 'Enter') {
                performKBSearch();
            }
        });

        function performKBSearch() {
            var query = $('#kb-search-input').val().trim();

            $.ajax({
                url: jocsoftSupportData.rootUrl + 'kb/search?q=' + encodeURIComponent(query),
                method: 'GET',
                success: function (res) {
                    var $grid = $('#kb-results-grid');
                    $grid.empty();

                    if (res.articles && res.articles.length > 0) {
                        res.articles.forEach(function (art) {
                            var card = '<div class="kb-article-card">' +
                                '<div class="kb-tag">' + art.type + '</div>' +
                                '<h4>' + art.title + '</h4>' +
                                '<div class="kb-category">' + art.category + '</div>' +
                                '<p>' + art.excerpt + '</p>' +
                                '<a href="' + art.link + '" class="kb-read-link">Read Standard Procedure &rarr;</a>' +
                                '</div>';
                            $grid.append(card);
                        });
                    } else {
                        $grid.html('<p style="color:#64748b; padding:20px;">No matching manuals or tutorials found for "' + query + '". Contact support if you need custom documentation.</p>');
                    }
                }
            });
        }
    });

})(jQuery);
