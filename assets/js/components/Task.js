import React from 'react';
import ListGroup from 'react-bootstrap/ListGroup';

const Task = ({name, dueDate, encodedUuid}) => (
	<ListGroup.Item>
		<h5>
			<a href={'/task/'+encodedUuid}>{name}</a>
		</h5>
		<p>Due: {dueDate ?? 'n/a'}</p>
	</ListGroup.Item>
)

export default Task;