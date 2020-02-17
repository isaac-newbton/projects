import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Task from '../../components/Task';
import ListGroup from 'react-bootstrap/ListGroup';

const ViewProject = props => {
	return (
		<Container>
			<Row>
				<Col>
					<h1>Viewing: {props.project.name}</h1>
					<Form.Control name="dueDate" onChange={event => props.updateProject(event)} type="date" defaultValue={props.project.dueDate} />
				</Col>
			</Row>
			<Row>
				<Col>
					<ListGroup>
					{props.project.tasks.map(task => {
						if (task.active){ // TODO: refactor me ...someday
							return (
								<div key={task.encodedUuid}>
									<Task name={task.name} dueDate={task.dueDate} encodedUuid={task.encodedUuid}/>
								</div>
							)
						}
					})}
					</ListGroup>
				</Col>
			</Row>
		</Container>
	)
}

export default ViewProject;