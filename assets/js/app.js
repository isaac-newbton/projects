import React, { Component, Fragment } from 'react';
import ReactDOM from 'react-dom';

class App extends Component {
	render() {
		return (
			<Fragment>React app</Fragment>
		)
	}
}

ReactDOM.render(<App/>, document.getElementById('root'))