import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route } from 'react-router-dom';
import ProjectScreen from './screens/ProjectScreen';
import NewProject from './screens/NewProject';
import TaskScreen from './screens/TaskScreen';

class App extends Component {
	render() {
		return (
			<BrowserRouter>
			<Switch>
				<Route exact path="/">
					<NewProject/>
				</Route>
				<Route path="/project/:encodedUuid">
					<ProjectScreen />
				</Route>
				<Route path="/task/:encodedUuid">
					<TaskScreen/>
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