import React from 'react';
import ListGroup from 'react-bootstrap/ListGroup';

const Task = ({name, dueDate}) => (
	<ListGroup.Item>
		<h5>{name}</h5>
		<p>Due: {dueDate ?? 'n/a'}</p>
	</ListGroup.Item>
)

export default Task;