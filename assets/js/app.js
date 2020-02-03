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
				<Route exact path="/">
					<NewProject/>
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