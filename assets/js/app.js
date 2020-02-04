import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route } from 'react-router-dom';
import ProjectView from './screens/ProjectView';
import NewProject from './screens/NewProject';

class App extends Component {
	render() {
		return (
			<BrowserRouter>
			<Switch>
				<Route path="/project/:uuid">
					<ProjectView />
				</Route>
				<Route exact path="/">
					<NewProject/>
				</Route>
				<Route>
					TODO: create a 404 component here
				</Route>
			</Switch>
			</BrowserRouter>
		)
	}
}

ReactDOM.render(<App/>, document.getElementById('root'))