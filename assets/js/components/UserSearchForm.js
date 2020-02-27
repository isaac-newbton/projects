import React from 'react'
import PropTypes from 'prop-types';
import { Form, ListGroup } from 'react-bootstrap'
import { useState } from 'react'

const UserSearchForm = props => {
	const [users, setUsers] = useState([])
	const searchUsers = input => {
		input.trim() !== '' ?
		fetch("/api/v1/user/search", {
			method: "POST",
			body: JSON.stringify({searchValue: input})
		})
		.then(resp => resp.json()).then(resp => {
			!resp.error ? setUsers(resp) : (setUsers([]) && console.log(resp))
		}) :
		setUsers([]);
	}

	const handleSelect = user => props.onSelect(user)

	return (
		<>
		<Form>
			<Form.Control type="text" placeholder="Search users" onChange={e => searchUsers(e.target.value)}/>
		</Form>
		<ListGroup>
			{users.map((user, index) => {
				return (
					<ListGroup.Item key={index} onClick={() => handleSelect(user)}>
						{ user.displayName ?? user.email ?? user.mobileNumber }
					</ListGroup.Item>
				)
			})}
		</ListGroup>
		</>
	)
}

UserSearchForm.propTypes = {
	/**
	 * Takes a callback function, providing a selected user object to it upon click
	 */
	onSelect : PropTypes.func
}

UserSearchForm.defaultProps = {
	onSelect : user => console.log(user)
}

export default UserSearchForm;