import { LocationFinder } from '@/assets/apps/LocationFinder';
import { createRoot } from 'react-dom/client';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { APIProvider } from '@vis.gl/react-google-maps';
import { ViewportProvider } from '@apps/ViewportProvider';
import { ServiceChecker } from '@apps/ServiceChecker';
import Questionnaire from '@apps/Questionnaire';
import { isLocationState, isLocationCategory } from '@apps/useLocations';

import GATracking from '../scripts/GATracking';

const queryClient = new QueryClient();

document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;

  const locationSearch = document.getElementById('location-service');
  const serviceChecker = document.getElementById('service-checker');
  const questionnaireRoot = document.createElement('div');
  questionnaireRoot.classList.add('questionnaire-overlay');

  const questionnaireEntryButton = document.querySelectorAll(
    '.questionnaire-entry-button'
  );

  const questionnaireCustomEntryButtons = document.querySelectorAll(
    '.questionnaire-custom-entry-button'
  );

  if (locationSearch) {
    const root = createRoot(locationSearch);

    const defaultLocationCategory =
      locationSearch.dataset.defaultLocationCategory;
    const defaultState = locationSearch.dataset.defaultState;

    root.render(
      <QueryClientProvider client={queryClient}>
        <ViewportProvider>
          <APIProvider
            apiKey={'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug'}
            libraries={['places', 'geometry']}
          >
            <LocationFinder
              defaultLocationCategory={
                defaultLocationCategory &&
                isLocationCategory(defaultLocationCategory)
                  ? defaultLocationCategory
                  : undefined
              }
              defaultState={
                defaultState && isLocationState(defaultState)
                  ? defaultState
                  : undefined
              }
            />
          </APIProvider>
        </ViewportProvider>
      </QueryClientProvider>
    );
  }

  if (serviceChecker) {
    const root = createRoot(serviceChecker);

    root.render(<ServiceChecker />);

    root.render(
      <QueryClientProvider client={queryClient}>
        <APIProvider
          apiKey={'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug'}
          libraries={['places', 'geometry']}
        >
          <ServiceChecker />
        </APIProvider>
      </QueryClientProvider>
    );
  }

  if (questionnaireEntryButton.length > 0) {
    for (const button of questionnaireEntryButton) {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        body.appendChild(questionnaireRoot);
        const root = createRoot(questionnaireRoot);

        root.render(
          <QueryClientProvider client={queryClient}>
            <Questionnaire
              onClose={() => {
                root.unmount();
                questionnaireRoot.remove();
              }}
            />
          </QueryClientProvider>
        );
      });
    }
  }

  if (questionnaireCustomEntryButtons.length > 0) {
    for (const button of questionnaireCustomEntryButtons) {
      button.addEventListener(
        'click',
        function (this: HTMLElement, event: Event) {
          event.preventDefault();
          body.appendChild(questionnaireRoot);
          const root = createRoot(questionnaireRoot);

          const questionIndex = this.dataset.question;

          if (questionIndex === undefined) {
            console.error('Question index is undefined');

            return;
          }

          const defaults = [
            {
              // id: 'q2-which-option-best-describes-your-needs-9df416',
              id: 'q2-how-would-you-describe-your-needs-9df416',
              model: new Map([
                // ['entry_question', { value: ["I'm looking for myself"] }],
                ['entry_question', { value: ["I’m looking for services for myself"] }],
              ]),
            },
            {
              id: 'q2-who-are-you-assisting-9df416',
              model: new Map([
                ['entry_question', { value: ["I'm looking for services for someone else"] }],
              ]),
            },
            {
              // id: 'q2-what-is-your-area-of-interest-9df416',
              id: 'q2-which-service-area-are-you-interested-in-9df416',
              model: new Map([
                ['entry_question', { value: ["I'm looking for a job"] }],
              ]),
            },
            {
              id: 'q2-how-would-you-like-to-get-involved-9df416',
              model: new Map([
                ['entry_question', { value: ["I'm looking to volunteer"] }],
              ]),
            },
            // {
            //   id: 'q2-how-would-you-like-to-get-involved-9df416',
            //   model: new Map([
            //     ['entry_question', { value: ["I'm looking to volunteer"] }],
            //   ]),
            // },
          ];

          const defaultQuestion = defaults[parseInt(questionIndex)];

          const clickedAnswer = defaultQuestion.model.get('entry_question').value
          GATracking.sendEvent({
            event: 'Personalisation Start: Personalisation Launcher',
            category: 'questionnaire',
            action: 'click',
            label: clickedAnswer,
            value: clickedAnswer
          });

          root.render(
            <QueryClientProvider client={queryClient}>
              <Questionnaire
                onClose={() => {
                  root.unmount();
                  questionnaireRoot.remove();
                }}
                defaultQuestionId={defaultQuestion.id}
                defaultModel={defaultQuestion.model}
              />
            </QueryClientProvider>
          );
        }
      );
    }
  }
});
