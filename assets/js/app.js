import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route, Redirect } from 'react-router-dom';
import ProjectScreen from './screens/ProjectScreen';
import NewProject from './screens/NewProject';
import TaskScreen from './screens/TaskScreen';
import NewUser from './screens/NewUser';
import SignIn from './screens/SignIn';
import { useEffect } from 'react';

const App = () => {

	const [user, setUser] = useState()

	useEffect(() => {
		fetch("/api/v1/auth")
		.then(resp => resp.json())
		.then(resp => resp.error ?? setUser(resp))
	}, [])


	const handleLogin = data => {
		fetch("/api/v1/login", {
			method: "POST",
			headers: {
				'Accept': 'application/json',
      			'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})
		.then(resp => resp.json())
		.then(resp => {
			resp.error ? setUser(resp) : console.log(resp)
		})
	}

	return (
		<BrowserRouter>
		<Switch>
			<Route exact path="/">
				<NewProject/>
			</Route>
			<Route path="/signup">
				<NewUser/>
			</Route>
			<Route path="/login">
				{user ? <Redirect to="/" /> : <SignIn handleLogin={handleLogin} />}
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

ReactDOM.render(<App/>, document.getElementById('root'))