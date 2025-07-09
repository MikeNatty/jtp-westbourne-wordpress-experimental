type SwiperCardProps = {
  images: string[];
  labels: string[];
  title: string;
  pills: string[];
  link?: string;
};

export function SwiperCard({
  link,
  images,
  labels,
  title,
  pills,
}: SwiperCardProps) {
  return (
    <div className="card">
      <div className="card-media">
        <div className="media">
          <swiper-container navigation="true" pagination="true" loop="true">
            {images.map((image, index) => {
              return (
                <swiper-slide key={image + index}>
                  <img
                    src={image}
                    width="452"
                    height="280"
                    alt=""
                    className="placeholder"
                  />
                </swiper-slide>
              );
            })}
          </swiper-container>
        </div>
      </div>
      <div className="card-text">
        <div className="card-text-top">
          <div className="card-labels">
            {labels?.map((label, index) => <p key={index}>{label}</p>)}
          </div>
          {link ? (
            <a href={link} className="card-link">
              <h3 className="card-title">{title}</h3>
            </a>
          ) : (
            <h3 className="card-title">{title}</h3>
          )}
        </div>
        <div className="card-text-bottom">
          <div className="card-pills">
            {pills.map((pill, index) => (
              <p key={index} className="pill" data-pill-theme="secondary">
                {pill}
              </p>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
