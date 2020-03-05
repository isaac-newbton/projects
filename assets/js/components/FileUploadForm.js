import React from 'react';
import PropTypes from 'prop-types';
import Form from 'react-bootstrap/Form';

const FileUploadForm = ({handleSubmit}) => {

	const handleAddFiles = e => {
		const fileData = new FormData();
		for (const file of e.target.files){
			fileData.append('file', file, file.name)
		}
		e.target.value = null
		handleSubmit(fileData)
	}

	if (handleSubmit){
		return (
			<Form>
				<Form.Group controlId="fileUpload">
					<Form.Label controlId="fileUpload" className="btn btn-link">Upload File</Form.Label>
					<Form.Control style={{display:'none'}} placeholder="Upload" onChange={e => handleAddFiles(e)} type="file"></Form.Control>
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