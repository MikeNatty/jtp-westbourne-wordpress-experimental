export const SkeletonCard = ({
  direction,
}: {
  direction: 'row' | 'column';
}) => {
  return (
    <div className="skeleton-card" data-direction={direction}>
      <div className="skeleton-card-media"></div>
      <div className="skeleton-card-content">
        <div className="skeleton-card-text"></div>
        <div className="skeleton-card-pills">
          <div className="skeleton-pill" />
          <div className="skeleton-pill" />
        </div>
      </div>
    </div>
  );
};

export const SkeletonQuestion = () => {
  return (
    <div className="skeleton-question">
      <div className="skeleton-question-title"></div>
      <div className="skeleton-question-description-full"></div>
      <div className="skeleton-question-description"></div>
      <div className="skeleton-question-options">
        <div className="skeleton-option" />
        <div className="skeleton-option" />
        <div className="skeleton-option" />
        <div className="skeleton-option" />
        <div className="skeleton-option" />
      </div>
    </div>
  );
};
