import React from 'react';
import { Form, Button, Container, Row, Col } from 'react-bootstrap';
import { useState } from 'react';

const SignIn = props => {

	const [login, setLogin] = useState('');
	const [password, setPassword] = useState('');

	const handleSubmit = e => {
		e.preventDefault()
		props.handleLogin({
			"username": login,
			"password": password
		})
	}

	return (
		<Container>
			<Row>
				<Col>
					<Form onSubmit={e => handleSubmit(e)}>
						<Form.Group>
							<Form.Label>Mobile Number or Email Address</Form.Label>
							<Form.Control type="text" value={login} onChange={e => setLogin(e.target.value)}/>

							<Form.Label>Password</Form.Label>
							<Form.Control type="password" value={password} onChange={e => setPassword(e.target.value)} />

							<Button type="submit">Login</Button>
						</Form.Group>
					</Form>
				</Col>
			</Row>
		</Container>
	)
}

export default SignIn