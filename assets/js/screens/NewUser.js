import React, { Fragment } from 'react';
import { useHistory } from 'react-router-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import FormGroup from 'react-bootstrap/FormGroup';
import FormControl from 'react-bootstrap/FormControl';
import FormText from 'react-bootstrap/FormText';
import { Button } from 'react-bootstrap';

const queryString = require('query-string');

export default function NewUser(props) {
	const params = queryString.parse(props.location.search);

	const [userEmail, setUserEmail] = React.useState('');
	const [userMobileNumber, setUserMobileNumber] = React.useState('');
	const [userPassword, setUserPassword] = React.useState('');
	const [displayName, setDisplayName] = React.useState('');
	const [encodedTaskUuid, setEncodedTaskUuid] = React.useState(params.task);

	const createUser = e => {
		e.preventDefault();
		let user = new FormData();
		user.append('email', userEmail);
		user.append('mobileNumber', userMobileNumber);
		user.append('displayName', displayName);
		user.append('password', userPassword);
		if (encodedTaskUuid) user.append('encodedTaskUuid', encodedTaskUuid);
		const response = fetch('/api/v1/user/create', {
			method: 'POST',
			body: user,
		})
			.then(r => r.json())
			.then(j => {
				console.log(j);
				if (j.user) {
					//worked
				}
			});
	};

	return (
		<Fragment>
			<Container>
				<Form onSubmit={createUser}>
					<Row>
						<Col>
							<h1>
								Enter your email OR mobile number to create an
								account
							</h1>
						</Col>
					</Row>
					<Row>
						<Col>
							<FormGroup>
								<FormControl
									id='inputEmail'
									type='text'
									placeholder='Email address'
									onChange={e => {
										setUserEmail(e.target.value);
										document.getElementById(
											'inputMobileNumber',
										).required =
											0 ==
											e.target.value.toString().length;
									}}
									required={true}
								/>
								<FormText className='text-muted'>
									Enter your email
								</FormText>
							</FormGroup>
						</Col>
						<Col>
							<FormGroup>
								<FormControl
									id='inputMobileNumber'
									type='text'
									placeholder='Mobile number'
									onChange={e => {
										setUserMobileNumber(e.target.value);
										document.getElementById(
											'inputEmail',
										).required =
											0 ==
											e.target.value.toString().length;
									}}
									required={true}
								/>
								<FormText className='text-muted'>
									Enter your mobile number
								</FormText>
							</FormGroup>
						</Col>
					</Row>
					<Row>
						<Col>
							<FormGroup>
								<FormControl
									type='text'
									placeholder='Display Name'
									onChange={e =>
										setDisplayName(e.target.value)
									}
									required={true}
								/>
								<FormText className='text-muted'>
									Enter a display name - This is how others
									will find you
								</FormText>
							</FormGroup>
						</Col>
					</Row>
					<Row>
						<Col>
							<FormGroup>
								<FormControl
									type='password'
									placeholder='Password'
									onChange={e =>
										setUserPassword(e.target.value)
									}
									required={true}
								/>
								<FormText className='text-muted'>
									Enter a secure password (7 character
									minimum)
								</FormText>
							</FormGroup>
						</Col>
					</Row>
					<Row>
						<Col>
							<Button variant='primary' type='submit'>
								Sign up
							</Button>
						</Col>
					</Row>
				</Form>
			</Container>
		</Fragment>
	);
}
