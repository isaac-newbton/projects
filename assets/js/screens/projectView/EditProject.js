import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';

const EditProject = props => {
	return (
		<Container>
			<Row>
				<Col>
					<h1>{props.project.name}</h1>
					<Form.Control name="dueDate" onChange={event => props.updateProject(event)} type="date" defaultValue={props.project.dueDate} />
				</Col>
			</Row>
		</Container>
	)
}

export default EditProject;