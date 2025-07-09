import type { QuestionModel, QuestionNextModel } from '@apps/useQuestionnaire';
import { RadioButton } from '@apps/components/RadioButton';
import { CheckboxButton } from '@apps/components/CheckboxButton';

type QuestionProps = {
  value: string[];
  onChange: (value: { value: string[]; next: QuestionNextModel }) => void;
  question: QuestionModel;
};

export function Question({
  value: questionValue,
  onChange,
  question,
}: QuestionProps) {
  return (
    <>
      <p className="question-level">Question {question.level}</p>
      <h5 className="question-title">{question.title}</h5>
      <p className="question-description">{question.description}</p>
      <div className="question-options">
        {(() => {
          switch (question.options.type) {
            case 'singleSelect':
              return question.options.items.map((option, index) => (
                <RadioButton
                  value={questionValue[0]}
                  key={option.value + index}
                  option={{
                    value: option.value,
                    label: option.value,
                  }}
                  onChange={(value) => {
                    onChange({
                      value: [value],
                      next: option.next,
                    });
                  }}
                  name={`radio-${question.id}`}
                  data-size={'small'}
                />
              ));

            case 'multiSelect': {
              const multiSelectOptions = question.options;

              return multiSelectOptions.items.map((option, index) => (
                <CheckboxButton
                  key={option.value + index}
                  option={{
                    value: option.value,
                    label: option.value,
                  }}
                  onChange={({ value, checked }) => {
                    if (checked) {
                      onChange({
                        value: [...questionValue, value],
                        next: multiSelectOptions.next,
                      });
                    } else {
                      onChange({
                        value: questionValue.filter((v) => v !== option.value),
                        next: multiSelectOptions.next,
                      });
                    }
                  }}
                  name={`checkbox-${question.id}`}
                />
              ));
            }
          }
        })()}
      </div>
    </>
  );
}
