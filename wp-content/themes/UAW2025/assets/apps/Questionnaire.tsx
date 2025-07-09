import { useQuestionnaire, QuestionMap } from '@apps/useQuestionnaire';
import type { QuestionModel, QuestionNextModel } from '@apps/useQuestionnaire';
import { useState, useRef } from 'react';
import { Question } from '@apps/components/Question';
import { SwitchTransition, CSSTransition } from 'react-transition-group';
import { SkeletonQuestion } from '@apps/components/Skeletons';
// import "choices.js/public/assets/styles/choices.css";

import GATracking from '../scripts/GATracking'

// Helpers
function getSelectedOptionsIndex(
  question: QuestionModel,
  selectedOptions: string[]
) {
  const answersIndex = question.options.items
    .filter((option) => {
      return selectedOptions.includes(option.value);
    })
    .map((_, index) => index + 1);

  return answersIndex;
}

function getSelectedOptionsQueryParams(
  question: QuestionModel,
  selectedOptions: string[]
) {
  const answersIndex = getSelectedOptionsIndex(question, selectedOptions);

  return answersIndex.map((item) => `final_answers[]=${item}`).join('&');
}

type QuestionnaireProps = {
  onClose: () => void;
  defaultQuestionId?: string;
  defaultModel?: QuestionnaireModel;
};

type QuestionnaireModel = Map<
  string,
  {
    value: string[];
  }
>;

function Questionnaire({
  onClose,
  defaultQuestionId,
  defaultModel,
}: QuestionnaireProps) {
  const { data, isLoading, error } = useQuestionnaire();

  const [next, setNext] = useState<QuestionNextModel | null>(
    defaultQuestionId ? { type: 'continue', value: defaultQuestionId } : null
  );

  const [questionnaireModel, setQuestionnaireModel] =
    useState<QuestionnaireModel>(defaultModel ?? new Map());

  const [currentQuestionId, setCurrentQuestionId] = useState<string | null>(
    defaultQuestionId ?? 'entry_question'
  );

  const [isTransitioning, setIsTransitioning] = useState(false);
  const [direction, setDirection] = useState<'forward' | 'back'>('forward');
  const nodeRef = useRef(null);

  const questions = data ? data : null;

  const currentQuestion =
    questions && currentQuestionId ? questions[currentQuestionId] : null;

  const previousQuestion =
    questions && currentQuestion && currentQuestion.previous
      ? questions[currentQuestion.previous]
      : null;

  const isSelectedOptionsEmpty = (() => {
    if (!currentQuestion) return true;

    const currentValue = questionnaireModel.get(currentQuestion.id)?.value;

    if (!currentValue) return true;

    return currentValue.length === 0;
  })();

  const handleClose = () => {

    GATracking.sendEvent({
      event: 'Personalisation Abandon',
      category: 'questionnaire',
      action: 'click',
      label: currentQuestion?.title,
      value: {
        step: currentQuestion?.level,
        question: currentQuestion?.title,
      }
    });

    onClose();
  };

  const handleBack = () => {
    if (!previousQuestion) return;

    setDirection('back');

    // push update to macro task to trigger transition after direction update
    setTimeout(() => {
      setCurrentQuestionId(previousQuestion.id);

      // Get the next state based on the previous question's selected options
      const previousValue =
        questionnaireModel.get(previousQuestion.id)?.value ?? [];

      if (previousValue.length > 0) {
        setNext({ type: 'continue', value: currentQuestionId ?? '' });
      } else {
        setNext(null);
      }

      GATracking.sendEvent({
        event: 'Personalisation Prev',
        category: 'questionnaire',
        action: 'click',
        label: previousQuestion.title,
        value: {
          question: previousQuestion.title,
          step: previousQuestion.level,
        }
      });

    }, 0);
  };

  return (
    <div className="questionnaire">
      <header className="questionnaire-header">
        <button
          type="button"
          className={`button questionnaire-back-button ${
            previousQuestion ? '' : 'questionnaire-back-button--hidden'
          }`}
          data-size="xx-small"
          data-icon-gap="medium"
          data-button-theme="tertiary"
          data-icon="chevron-left"
          disabled={isLoading || isTransitioning || !previousQuestion}
          onClick={handleBack}
        >
          Back
        </button>

        <button
          type="button"
          className="button--icon questionnaire-close-button"
          data-size="x-small"
          data-icon="close-large"
          data-button-theme="tertiary"
          onClick={handleClose}
          disabled={isLoading || isTransitioning}
        ></button>
      </header>

      {isLoading && (
        <div className="questionnaire-skeleton">
          <SkeletonQuestion />
        </div>
      )}

      {error ? <p>{error.message}</p> : null}
      {currentQuestion && (
        <>
          <SwitchTransition mode="out-in">
            <CSSTransition
              key={currentQuestion.id}
              nodeRef={nodeRef}
              timeout={300}
              classNames={`question-${direction}`}
              onExit={() => setIsTransitioning(true)}
              onExited={() => setIsTransitioning(false)}
            >
              <div ref={nodeRef}>
                <Question
                  value={
                    questionnaireModel.get(currentQuestion.id)?.value ?? []
                  }
                  question={currentQuestion}
                  onChange={({ value, next }) => {
                    setQuestionnaireModel(
                      new Map(questionnaireModel).set(currentQuestion.id, {
                        value,
                      })
                    );

                    if (value.length === 0) {
                      setNext(null);
                    } else {
                      setNext(next);
                    }

                    GATracking.sendEvent({
                      event: 'Personalisation Answer',
                      category: 'questionnaire',
                      action: 'click',
                      label: currentQuestion.title,
                      value: {
                        step: currentQuestion.level,
                        question: currentQuestion.title,
                        answer: value,
                      }
                    });

                    // GATracking.sendEvent({
                    //   event: `Personalisation Answer: Step ${currentQuestion.level} | Q currentQuestion.title | Answer ${JSON.stringify(value)}`,
                    //   category: 'questionnaire',
                    //   action: 'click',
                    //   label: currentQuestion.title,
                    //   value: null
                    // });


                  }}
                />
              </div>
            </CSSTransition>
          </SwitchTransition>
          <footer className="questionnaire-footer">
            <button
              type="button"
              className="button button--reversed questionnaire-next-button"
              data-button-theme="primary"
              disabled={!next || isTransitioning || isSelectedOptionsEmpty}
              data-icon="chevron-right-large"
              data-icon-gap="medium"
              onClick={() => {
                if (questions && next) {
                  switch (next.type) {
                    case 'continue': {
                      setDirection('forward');

                      setTimeout(() => {
                        setCurrentQuestionId(next.value);
                      }, 0);

                      // For tracking purposes, we need to grab the next model here
                      const nextQuestion = questions[next.value];
                      GATracking.sendEvent({
                        event: 'Personalisation Next',
                        category: 'questionnaire',
                        action: 'click',
                        label: nextQuestion.title,
                        value: {
                          question: nextQuestion.title,
                          step: nextQuestion.level,
                        }
                      });
                    }
                      break;

                    case 'end': {
                      const queryParams = getSelectedOptionsQueryParams(
                        currentQuestion,
                        questionnaireModel.get(currentQuestion.id)?.value ?? []
                      );
                      window.location.href = `${next.value}?${queryParams}`;

                      GATracking.sendEvent({
                        event: 'Personalisation End',
                        category: 'questionnaire',
                        action: 'click',
                        label: next.value,
                        value: {
                          url: next.value,
                        }
                      });

                      break;
                    }
                  }
                }
              }}
            >
              Continue
            </button>
          </footer>
        </>
      )}
    </div>
  );
}

export default Questionnaire;
