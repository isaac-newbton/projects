import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Form, Button } from 'react-bootstrap';

const CommentForm = ({handleSubmit}) => {
	const [comment, setComment] = useState('')

	const handleFormSubmit = e => {
		e.preventDefault()
		setComment('')
		handleSubmit(comment)
	}
	if (handleSubmit){
		return (
			<Form onSubmit={e => handleFormSubmit(e)}>
			<Form.Control onChange={e => setComment(e.target.value)} placeholder="Write a new comment..." as="textarea" value={comment}></Form.Control>
				<Button type="submit" className="float-right">Submit</Button>
			</Form>
		)
	}
	return null
}

CommentForm.propTypes = {
	handleSubmit: PropTypes.func.isRequired,
};

export default CommentForm;