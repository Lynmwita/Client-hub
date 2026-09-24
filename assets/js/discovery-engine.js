/**
 * Jocsoft Solution Discovery Engine & AI Advisor JavaScript
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        var surveyData = {
            org_type: '',
            area_of_interest: '',
            main_challenge: '',
            full_name: '',
            organization: '',
            email: '',
            phone: '',
            job_title: ''
        };

        var currentLeadId = 0;
        var chatHistory = [];

        // 1. Option Card Selection
        $('.option-card').on('click', function () {
            var $card = $(this);
            var key = $card.data('key');
            var val = $card.data('value');

            $card.siblings().removeClass('selected');
            $card.addClass('selected');

            surveyData[key] = val;

            // Auto advance after slight delay for responsive feel
            setTimeout(function () {
                if (key === 'org_type') {
                    goToStep(2);
                } else if (key === 'area_of_interest') {
                    goToStep(3);
                } else if (key === 'main_challenge') {
                    renderValuePreview();
                    goToStep(4);
                }
            }, 180);
        });

        // 2. Back buttons
        $('.back-btn').on('click', function () {
            var backStep = $(this).data('back');
            goToStep(backStep);
        });

        function goToStep(stepNumber) {
            $('.jocsoft-step-card').hide();
            $('#step-' + stepNumber).fadeIn(200);

            // Update progress bar
            $('.progress-step').each(function () {
                var step = parseInt($(this).data('step'), 10);
                if (step < stepNumber) {
                    $(this).removeClass('active').addClass('completed');
                } else if (step === stepNumber) {
                    $(this).addClass('active').removeClass('completed');
                } else {
                    $(this).removeClass('active completed');
                }
            });
        }

        // 3. Render Value Preview (Step 4)
        function renderValuePreview() {
            var title = 'Tailored Enterprise Solution';
            var desc = 'Centralized digital system designed to eliminate manual bottlenecks.';

            if (surveyData.org_type === 'school' || surveyData.org_type === 'university') {
                title = 'SomaSmart Digital Learning & School Management Architecture';
                desc = 'Automated coursework, student progress tracking, fee receipts, and SMS communication.';
            } else if (surveyData.org_type === 'sacco') {
                title = 'Microsoft Dynamics Navision SACCO Core Financial System';
                desc = 'Streamlined loan approvals, member shares registry, SASRA compliance, and M-Pesa integration.';
            } else if (surveyData.org_type === 'hospital') {
                title = 'MedStar Hospital Information & Management Suite';
                desc = 'Connected EMR doctor workbench, pharmacy dispensary, OPD/IPD, and insurance billing.';
            }

            $('#preview-system-title').text(title);
            $('#preview-system-desc').text(desc);
        }

        // 4. Handle 4-Field Unlock Form Submission
        $('#jocsoft-unlock-form').on('submit', function (e) {
            e.preventDefault();

            surveyData.full_name    = $('#joc_name').val().trim();
            surveyData.organization = $('#joc_org').val().trim();
            surveyData.email        = $('#joc_email').val().trim();
            surveyData.phone        = $('#joc_phone').val().trim();
            surveyData.job_title    = $('#joc_role').val().trim();

            var $submitBtn = $('#unlock-submit-btn');
            $submitBtn.prop('disabled', true).text('Generating Your Solution Snapshot...');

            $.ajax({
                url: jocsoftHubData.rootUrl + 'discovery/submit',
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', jocsoftHubData.nonce);
                },
                contentType: 'application/json',
                data: JSON.stringify(surveyData),
                success: function (response) {
                    $submitBtn.prop('disabled', false).text('View Solution Snapshot & Advisory Options');
                    if (response.success && response.snapshot) {
                        currentLeadId = response.lead_id;
                        renderFinalSnapshot(response.snapshot);
                        goToStep(5);
                    }
                },
                error: function () {
                    $submitBtn.prop('disabled', false).text('View Solution Snapshot & Advisory Options');
                    alert('Unable to generate solution snapshot. Please check your internet connection.');
                }
            });
        });

        // 5. Render Final Solution Snapshot
        function renderFinalSnapshot(snapshot) {
            $('#snapshot-title').text(snapshot.primary_product + ' for ' + (surveyData.organization || 'Your Institution'));
            $('#snapshot-tagline').text(snapshot.product_tagline);

            var $modulesList = $('#snapshot-modules');
            $modulesList.empty();
            if (snapshot.modules && snapshot.modules.length > 0) {
                snapshot.modules.forEach(function (mod) {
                    $modulesList.append('<li>' + mod + '</li>');
                });
            }

            $('#snapshot-impact').text(snapshot.business_impact);
        }

        // 6. Launch AI Advisor Dialogue
        $('#launch-ai-advisor-btn').on('click', function () {
            $('#jocsoft-ai-chat-section').slideDown(250);
            $('html, body').animate({
                scrollTop: $('#jocsoft-ai-chat-section').offset().top - 40
            }, 300);
        });

        // 7. Quick Reply Button Click
        $(document).on('click', '.quick-reply-btn', function () {
            var text = $(this).text();
            $('#ai-user-input').val(text);
            $('#ai-chat-form').trigger('submit');
        });

        // 8. AI Chat Form Submit
        $('#ai-chat-form').on('submit', function (e) {
            e.preventDefault();

            var message = $('#ai-user-input').val().trim();
            if (!message) return;

            // Append user message
            appendChatMessage(message, 'user');
            $('#ai-user-input').val('');
            $('#ai-quick-options').empty();

            chatHistory.push({ sender: 'user', text: message });

            // Show typing indicator
            var $typing = $('<div class="chat-msg msg-advisor typing-indicator"><div class="msg-bubble"><em>Jocsoft Advisor is analyzing your requirements...</em></div></div>');
            $('#ai-messages-container').append($typing);
            scrollChatToBottom();

            $.ajax({
                url: jocsoftHubData.rootUrl + 'ai-advisor/chat',
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', jocsoftHubData.nonce);
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    message: message,
                    history: chatHistory,
                    context: surveyData,
                    lead_id: currentLeadId
                }),
                success: function (res) {
                    $typing.remove();
                    if (res.success && res.data) {
                        appendChatMessage(res.data.reply, 'advisor');
                        chatHistory.push({ sender: 'advisor', text: res.data.reply });

                        // Render quick replies if any
                        if (res.data.suggested_options && res.data.suggested_options.length > 0) {
                            var $quick = $('#ai-quick-options');
                            $quick.empty();
                            res.data.suggested_options.forEach(function (opt) {
                                $quick.append('<button type="button" class="quick-reply-btn">' + opt + '</button>');
                            });
                        }
                    }
                },
                error: function () {
                    $typing.remove();
                    appendChatMessage('Our technical team is ready to discuss this with you directly at (+254) 732 447 447 or info@jocsoft.net.', 'advisor');
                }
            });
        });

        function appendChatMessage(text, sender) {
            var msgClass = (sender === 'user') ? 'msg-user' : 'msg-advisor';
            var $msg = $('<div class="chat-msg ' + msgClass + '"><div class="msg-bubble">' + text + '</div></div>');
            $('#ai-messages-container').append($msg);
            scrollChatToBottom();
        }

        function scrollChatToBottom() {
            var container = document.getElementById('ai-messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    });

})(jQuery);
