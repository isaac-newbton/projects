import React from 'react';
import ListGroup from 'react-bootstrap/ListGroup';

const Task = ({name, dueDate, encodedEditUuid, encodedViewUuid}) => (
	<ListGroup.Item>
		<h5>
			{encodedEditUuid ? <a href={'/task/'+encodedEditUuid}>{name}</a> : (encodedViewUuid ? <a href={'/task/'+encodedViewUuid}>{name}</a> : name)}
		</h5>
		<p>Due: {dueDate ?? 'n/a'}</p>
	</ListGroup.Item>
)

export default Task;