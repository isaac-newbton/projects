import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';

const ViewProject = props => {
	return (
		<Container>
			<Row>
				<Col>
					<h1>Viewing: {props.project.name}</h1>
					<Form.Control name="dueDate" onChange={event => props.updateProject(event)} type="date" defaultValue={props.project.dueDate} />
				</Col>
			</Row>
			{/* TODO: Return the tasks here */}
			{/* <Row>
				<Col>
					{props.project.tasks.map(task => <div>{task.name}</div>)}
				</Col>
			</Row> */}
		</Container>
	)
}

export default ViewProject;