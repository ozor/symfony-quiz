

const $ = require('jquery');
$(document).ready(function() {
    function displayQuestionContainer()
    {
        $('.loading').hide();
        $('.submit-block').hide();

        let current = $('.current-question').val();

        let i = 1;
        $('.question-container').hide();
        if (current) {
            i = $('.question-' + current).show().data('index');
        } else {
            $('.index-0').show();
        }
        i++;

        $('.page-link').removeClass('active');
        $('.linkIndex-' + i).addClass('active');
    }

    let quizApp = function() {
        $('.next-button').click(function() {
            let resultId = $(this).attr('data-resultId');

            let nextResultId = null;
            let next = $(this).attr('data-next');
            if (next) {
                nextResultId = $('.index-' + next).attr('data-resultId');
            } else {
                nextResultId = resultId;
            }

            let checkedInputs = [];
            $('.answers-' + resultId + ' input:checked').each(function () {
                checkedInputs.push($(this).attr('data-answer'));
            })

            let postData = {
                'answers': checkedInputs,
                'resultId': parseInt(resultId),
                'nextResultId': nextResultId ? parseInt(nextResultId) : null
            };
            $.post(update_url, postData).done(function (data) {
                if (next) {
                    $('.current-question').val(nextResultId);
                    displayQuestionContainer();
                } else {
                    $('#quiz-form').submit();
                }
            });
        });
    };

    displayQuestionContainer();
    quizApp();
});
