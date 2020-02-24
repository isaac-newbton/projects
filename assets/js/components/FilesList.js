import React from 'react';
import { ListGroup } from 'react-bootstrap';

const FilesList = ({files}) => {
	return files.map(file => <ListGroup.Item>{file.name}</ListGroup.Item>)
}

export default FilesList;