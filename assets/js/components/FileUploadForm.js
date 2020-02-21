import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Form, Button, Badge } from 'react-bootstrap';

const FileUploadForm = ({handleSubmit}) => {

	const handleAddFiles = fileList => {
		const fileData = new FormData();
		for (const file of fileList){
			fileData.append('file', file, file.name)
		}
		console.log(...fileData)
		handleSubmit(fileData)
	}


	if (handleSubmit){
		return (
			<Form>
				<Form.Group controlId="fileUpload">
					<Form.Label controlId="fileUpload" className="btn btn-link">Upload File</Form.Label>
					<Form.Control multiple={true} style={{display:'none'}} placeholder="Upload" onChange={e => handleAddFiles(e.target.files)} type="file"></Form.Control>
				</Form.Group>
			</Form>
		)
	}
	return null
}

FileUploadForm.propTypes = {
	handleSubmit: PropTypes.func.isRequired,
};

export default FileUploadForm;