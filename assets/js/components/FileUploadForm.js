import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Form, Button } from 'react-bootstrap';
import { Label } from 'react-bootstrap';

const FileUploadForm = ({handleSubmit}) => {
	const [files, setFiles] = useState([])

	const handleFiles = file => {
		console.log(file)
	}

	const handleFormSubmit = e => {
		e.preventDefault()
		setFiles(null)
		handleSubmit(files)
	}

	if (handleSubmit){
		return (
			<Form onSubmit={e => handleFormSubmit(e)}>
				<Form.Group controlId="fileUpload">
					<Form.Label controlId="fileUpload">Select File(s)</Form.Label>
					<Form.Control style={{display:'none'}} placeholder="Upload" onChange={e => handleFiles(e.target.files)} type="file"></Form.Control>
				</Form.Group>
				<Button type="submit" className="">Upload</Button>
			</Form>
		)
	}
	return null
}

FileUploadForm.propTypes = {
	handleSubmit: PropTypes.func.isRequired,
};

export default FileUploadForm;