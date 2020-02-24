import React, { useState } from 'react';
import { ListGroup } from 'react-bootstrap';

const FilesList = ({files}) => {
	return files.map((file, index) =>
		<ListGroup.Item key={index}>
			<a target="_blank" href={`/api/v1/file/${file.encodedUuid}`}>{file.name}</a>
		</ListGroup.Item>
	)
}

export default FilesList;