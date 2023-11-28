
function Button(props) {
  return (
    <div>
    <a className={props.classProp} href="https://www.atera.com">{props.text}</a>
    {props.classProp === 'pinkButton' ? (<p className="noCredit">No credit card required</p>) : ''}
    </div>
  )
}

export default Button;