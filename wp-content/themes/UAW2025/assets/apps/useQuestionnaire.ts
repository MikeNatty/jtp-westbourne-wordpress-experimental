import { useQuery } from '@tanstack/react-query';

export type QuestionModel = {
  id: string;
  title: string;
  description: string;
  previous: string | null;
  level: number;
  options:
    | {
        type: 'singleSelect';
        items: {
          value: string;
          next: QuestionNextModel;
        }[];
      }
    | {
        type: 'multiSelect';
        items: {
          value: string;
        }[];
        next: QuestionNextModel;
      };
};

export type QuestionNextModel = {
  type: 'continue' | 'end';
  value: string;
};

export type QuestionMap = Record<string, QuestionModel>;

function getQuestionnaire() {
  return fetch(`/wp-json/questionnaire/v1/questions`)
    .then((res) => res.json())
    .then((data) => data as QuestionMap);
}

export function useQuestionnaire() {
  const { data, isLoading, error } = useQuery({
    queryKey: ['questionnaire'],
    queryFn: () => getQuestionnaire(),
  });

  return { data, isLoading, error };
}
