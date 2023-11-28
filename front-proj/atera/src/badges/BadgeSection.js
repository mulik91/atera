import Badge from "./Badge";

function BadgeSection() {

  const pathToSvg = './assets/images/badges';

  const svgs = require.context ( '/public/assets/images/badges', true, /\.svg$/ );
  
  const svgPathes = svgs.keys();

  return (
    <section className="badgeSection">
      {svgPathes.map((src, index) => {
        src = src.replace('.', '');
        return <Badge key={index} imageSrc={pathToSvg + src} />
      })}
    </section>
  );
}

export default BadgeSection;