import { HomeCarePackage } from '@apps/useHomeCarePackages';

type HomeCarePackageCardProps = {
  homeCarePackage: HomeCarePackage;
};

export function HomeCarePackageCard({
  homeCarePackage,
}: HomeCarePackageCardProps) {
  return (
    <div className="home-care-package-card">
      <div className="home-care-package-card-content">
        <h4>{homeCarePackage.title}</h4>
        <p>{homeCarePackage.description}</p>
        <button type="button" data-size="small" className="button">
          {homeCarePackage['call-to-action']}
        </button>
      </div>
      <figure className="home-care-package-card-media">
        <img
          src={homeCarePackage.featured_image_url}
          alt={homeCarePackage.title}
        />
      </figure>
    </div>
  );
}
