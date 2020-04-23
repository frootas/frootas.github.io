import React from 'react';
import ReactDOM from 'react-dom';

const myfirstelement = (
  <div>
    <h1>Hello React!</h1>
    <p>testing</p>
  </div>
  
);

class Car extends React.Component {
  constructor() {
    super();
    //this.state = {color: "red"};
    this.state = {
      x: 2
      , y: 3
    };
  }
  render() {
  return <div>
      <h1>Hello React!</h1>
      <p>x = {this.state.x}</p>
      <p>y = {this.state.y}</p>
      <p>x + y = {this.state.x + this.state.y}</p>
    </div>
    //return <h2>Hi, I am a {this.state.car} {this.state.color}</h2>;
  }
}


//ReactDOM.render(myfirstelement, document.getElementById('root'));
ReactDOM.render(<Car />, document.getElementById('root'));
