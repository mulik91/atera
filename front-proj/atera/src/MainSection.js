import Button from "./Button";

function MainSection() {
  return (
    <section className="mainSection">
      <h1>Ready to take IT to the next level?</h1>
      <p>Explore all our features, free for 30 days.</p>
      <div className="mainButtons">
        <Button classProp="pinkButton" text="Start free trial"/>
        <Button classProp="darkButton" text="Book a demo"/>
      </div>
    </section>
  )
}

export default MainSection;