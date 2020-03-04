import React from 'react'
import PropTypes from 'prop-types'
import { Button, Modal, Form, InputGroup } from 'react-bootstrap'
import { useState } from 'react'

const InviteUser = props => {
	const [show, setShow] = useState(false)
	const [inputType, setInputType] = useState('📞')
	const [message, setMessage] = useState('')
	
	const parseInputType = () =>  inputType === '✉️' ? 'email' : 'tel';
	const [input, setInput] = useState({type: parseInputType()})

	const handleFormChange = () => {
		setInputType(inputType !== '📞' ? '📞' : '✉️')
		setInput({...input, type: parseInputType()})
	}

	const handleSubmit = e => {
		e.preventDefault()
		if (input.input) props.onSubmit(input)
	}
	
	return (
		<>
		<Button onClick={() => setShow(true)}>Invite User</Button>
		<Modal show={show} onHide={() => setShow(false)}>
			<Modal.Header closeButton>Invite someone to this task</Modal.Header>
			<Modal.Body>
				<Form onSubmit={e => handleSubmit(e)}>
					<InputGroup>
						<InputGroup.Prepend>
							<Button onClick={() => handleFormChange()}>{inputType}</Button>
						</InputGroup.Prepend>

						<Form.Control required onChange={e => setInput({...input, input: e.target.value})} placeholder={parseInputType()} type={parseInputType()}/>

						<InputGroup.Append>
							<Form.Control type='submit' value="Invite"/>
						</InputGroup.Append>
					</InputGroup>
				</Form>
			</Modal.Body>
		</Modal>
		</>
	)

}

InviteUser.propTypes = {
	/**
	 * Handles the submit of the invie user form, passing the data to the provided function
	 */
	onSubmit: PropTypes.func
}

InviteUser.defaultProps = {
	onSubmit: (input) => console.log('onSubmit prop not defined, here\'s the data ', input)
}

export default InviteUser