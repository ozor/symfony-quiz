

const $ = require('jquery');
$(document).ready(function() {
    let vars = {
        pagination_list_block: $('ul.pagination'),
        current_page_class: '',
        pagination_list_item: '',
        question_title_block: $('.question-title'),
        question_current_span: $('.questions-current'),
        answers_block: $('.answers-block'),
        next_button: $('.next-button'),
        next_button_value: '',
    };

    function insertQuestions(_step, _data)
    {
        for (let i = 0; i < _data.questions.results[_step].answers.length; i++) {
            let answer = _data.questions.results[_step].answers[i];
            vars.answers_block.append($('<div class="form-check"><input class="form-check-input" type="checkbox" value="' + answer.answerId + '">' + answer.answer + '</div>'));
            if (_step === _data.questions.count - 1) {
                vars.next_button.val('Save and finish').addClass('btn-primary').removeClass('btn-dark');
            } else {
                vars.next_button.val('Save and next').addClass('btn-dark').removeClass('btn-primary');
            }
            vars.next_button.data('step', _step);
        }
    }

    $.get(get_url, '').done(function (data) {

        $('.spinner-border').hide();
        $('.card-body-container').show();

        for (let step = 0; step < data.questions.count; step++) {
            vars.current_page_class = data.questions.current.index === step ? 'active' : '';
            vars.pagination_list_item = $('<li class="page-item link-index-' + step + ' ' + vars.current_page_class + '"><span class="page-link" href="#">' + (1 + step) + '</span></li>');
            vars.pagination_list_block.append(vars.pagination_list_item);

            if (step === data.questions.current.index) {
                vars.question_current_span.text(data.questions.current.index + 1);
                vars.question_title_block.append(data.questions.results[step].question.question);

                insertQuestions(step, data);
            }
        }

        let currentStep, nextStep;
        let resultId, nextResultId;
        $('.next-button').click(function() {
            currentStep = parseInt($(this).data('step'));
            nextStep = 1 + parseInt(currentStep);

            resultId = data.questions.results[currentStep].resultId;

            if (data.questions.count === nextStep) {
                nextResultId = null;
            } else {
                nextResultId = data.questions.results[nextStep].resultId;
            }

            let checkedInputs = [];
            $('.form-check-input:checked').each(function () {
                checkedInputs.push($(this).val());
            })

            let postData = {
                'answers': checkedInputs,
                'resultId': resultId,
                'nextResultId': nextResultId ? nextResultId : null
            };
            $.post(update_url, postData).done(function (response) {
                if (data.questions.count === nextStep) {
                    $('#quiz-form').submit();
                } else {
                    $('li.page-item').removeClass('active');
                    $('li.link-index-' + nextStep).addClass('active');

                    vars.next_button.data('step', nextStep);

                    vars.question_current_span.text(nextStep + 1);
                    vars.question_title_block.empty().append(data.questions.results[nextStep].question.question);

                    vars.answers_block.empty();

                    insertQuestions(nextStep, data);
                }
            });
        });
    });
});
