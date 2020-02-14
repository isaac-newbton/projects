import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';

const EditTask = props => {
	return (
		<Container>
			<Row>
				<Col>
					<h1>Editing Task: {props.task.name}</h1>
					<h2>
						<a href={'/project/' + props.task.project.encodedEditUuid ?? props.task.project.encodedViewUuid}>
							{props.task.project.name}
						</a>
					</h2>
				</Col>
			</Row>
		</Container>
	)
}

export default EditTask;