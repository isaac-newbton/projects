import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Form, Button, Badge } from 'react-bootstrap';

const FileUploadForm = ({handleSubmit}) => {
	const [files, setFiles] = useState([])

	const handleAddFiles = fileList => setFiles([...files, ...(Object.values(fileList).filter( file => !files.find(files => files.name === file.name) )) ])

	const handleFormSubmit = e => {
		e.preventDefault()
		handleSubmit(files)
	}

	const UploadButton = () => files.length > 0 ? <Button type="submit">Upload Selected Files</Button> : null;
	const QueuedFiles = () => files.map((file, index) => {
		return (
			<div key={index}>
			<Button size="sm" className='text-danger' variant="link">&times;</Button>
			{file.name}
			</div>
		)
	})

	if (handleSubmit){
		// console.log(files)
		return (
			<Form onSubmit={e => handleFormSubmit(e)}>
				<Form.Group controlId="fileUpload">
					<Form.Label controlId="fileUpload" className="btn btn-link">Add File(s)</Form.Label>
					<Form.Control style={{display:'none'}} multiple={true} placeholder="Upload" onChange={e => handleAddFiles(e.target.files)} type="file"></Form.Control>
					<QueuedFiles />
					<UploadButton />
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