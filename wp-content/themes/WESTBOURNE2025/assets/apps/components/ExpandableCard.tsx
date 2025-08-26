import React from 'react';

type ExpandableCardProps = {
  id: string;
  title: string;
  content: string;
  icon?: string | null;
  isExpanded: boolean;
  servicePage: string | null;
  onToggle: (id: string) => void;
};

export function ExpandableCard({
  id,
  title,
  content,
  icon,
  isExpanded,
  servicePage,
  onToggle,
}: ExpandableCardProps) {
  const contentRef = React.useRef<HTMLDivElement>(null);

  return (
    <>
    {/* <div className="expandable-card">
      <div className="expandable-card-container">
        {icon && (
          <figure className="expandable-card-media">
            <img src={icon}></img>
          </figure>
        )}
        <header className="expandable-card-heading">
          <h4 className="expandable-card-title">{title}</h4>
          <p className="expandable-card-description">{description}</p>
        </header>
        <button
          type="button"
          className={`button--icon expandable-card-button ${
            isExpanded ? 'expanded' : ''
          }`}
          data-icon="plus-large"
          onClick={() => onToggle(id)}
        >
          plus-large
        </button>
      </div>
      <section
        ref={contentRef}
        className="expandable-card-content"
        style={{
          maxHeight: isExpanded ? contentRef.current?.scrollHeight : '0px',
        }}
      >
        {content}
      </section>
    </div> */}


    <div 
      className="panels-row" 
      aria-expanded={ isExpanded ? "true" : "false"}
    >
      <div className="panels-heading">
        {icon && (
          <figure className="expandable-card-icon">
            <img src={icon}></img>
          </figure>
        )}
        <h3 className="panels-heading-text">
          {title}
        </h3>
        <button 
          data-refs="toggle" 
          title="Toggle Content" 
          className="trigger toggle-button" 
          type="button" 
          aria-expanded={ isExpanded ? "true" : "false"}
          aria-controls={`service-${id}`}
          onClick={() => onToggle(id)}
        ></button>
        <button
          type="button"
          className={`button--icon expandable-card-button ${
            isExpanded ? 'expanded' : ''
          }`}
          data-icon="plus-large"
        >
          plus-large
        </button>
      </div>
      <div 
        id={`content-${id}`}
        className="panels-content" 
        aria-hidden={ isExpanded ? "false" : "true"}
      >
        <div className="panels-content-inner">
          <p>{content}</p>

          {servicePage && (
            <a
              className="button"
              href={servicePage} 
              title="Read More" 
              data-size="regular"
            >
              Read More
            </a>
          )}
        </div>
      </div>
    </div>
    </>
  );
}
