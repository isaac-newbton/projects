import React from 'react';
import ListGroup from 'react-bootstrap/ListGroup';

const Task = (props) => (
	<ListGroup.Item>
		<h5>
			<a href={'/task/'+props.task.encodedUuid}>{props.task.name}</a>
		</h5>
		<p>Due: {props.task.dueDate ?? 'n/a'}</p>
		{props.task.assignedUser ? 
			props.task.assignedUser.displayName
			?? props.task.assignedUser.email
			?? props.task.assignedUser.mobileNumber
			: <span className='text-danger'>Unassigned</span>}
	</ListGroup.Item>
)

export default Task;