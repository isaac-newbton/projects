import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route, Redirect } from 'react-router-dom';
import ProjectScreen from './screens/ProjectScreen';
import NewProject from './screens/NewProject';
import TaskScreen from './screens/TaskScreen';
import NewUser from './screens/NewUser';
import SignIn from './screens/LogIn';
import { useEffect } from 'react';
import LogIn from './screens/LogIn';

const App = () => {

	const [user, setUser] = useState(null)

	useEffect(() => {
		// TODO: put this in an auth service component
		fetch("/api/v1/auth")
		.then(resp => resp.json())
		.then(resp => !resp.error ? setUser(resp) : null)
	} , [])
	
	const handleLogin = data => {
		// TODO: put this in an auth service component ?
		fetch("/api/v1/login", {
			method: "POST",
			headers: {
				'Accept': 'application/json',
      			'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})
		.then(resp => resp.json())
		.then(resp => !resp.error ? setUser(resp) : console.log(resp))
	}

	const LogOut = props => {
		const [loggedOut, setLoggedOut] = useState(null)
		fetch("/api/v1/logout")
		.then(resp => resp.json())
		.then(resp => {
			if(!resp.error){
				setLoggedOut(true)
			}
		})
		return loggedOut ? <Redirect to="/login" /> : <p>Logging out...</p>
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
				{user ? <Redirect to="/" /> : <LogIn handleLogin={handleLogin} />}
			</Route>
			<Route path="/logout">
				<LogOut />
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