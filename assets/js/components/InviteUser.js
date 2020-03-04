import React from 'react';
import PropTypes from 'prop-types';
import { Button, Modal, Form, InputGroup } from 'react-bootstrap';
import { useState } from 'react';

const InviteUser = props => {
	const [show, setShow] = useState(false);
	const [type, setType] = useState('tel');

	const [input, setInput] = useState('');

	const parseInputType = () => (type == 'email' ? '✉' : '📞');

	const handleFormChange = () => {
		setType(type == 'email' ? 'tel' : 'email');
	};

	const handleSubmit = e => {
		e.preventDefault();
		if (input.trim() !== '') props.onSubmit({ input, type });
	};

	return (
		<>
			<Button onClick={() => setShow(true)}>Invite User</Button>
			<Modal show={show} onHide={() => setShow(false)}>
				<Modal.Header closeButton>
					Invite someone to this task
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={e => handleSubmit(e)}>
						<InputGroup>
							<InputGroup.Prepend>
								<Button onClick={() => handleFormChange()}>
									{parseInputType()}
								</Button>
							</InputGroup.Prepend>

							<Form.Control
								required
								onChange={e => setInput(e.target.value)}
								placeholder={type}
								type={type}
							/>

							<InputGroup.Append>
								<Form.Control type='submit' value='Invite' />
							</InputGroup.Append>
						</InputGroup>
					</Form>
				</Modal.Body>
			</Modal>
		</>
	);
};

InviteUser.propTypes = {
	/**
	 * Handles the submit of the invie user form, passing the data to the provided function
	 */
	onSubmit: PropTypes.func,
};

InviteUser.defaultProps = {
	onSubmit: input =>
		console.log("onSubmit prop not defined, here's the data ", input),
};

export default InviteUser;
