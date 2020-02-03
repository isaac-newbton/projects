import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route } from 'react-router-dom';
import ProjectView from './screens/ProjectView';

class App extends Component {
	render() {
		return (
			<BrowserRouter>
			<Switch>
				<Route exact path="/">
					<h1>React App</h1>
				</Route>
				<Route path="/project">
					<ProjectView />
				</Route>
			</Switch>
			</BrowserRouter>
		)
	}
}

ReactDOM.render(<App/>, document.getElementById('root'))