import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';

const ViewTask = props => {
	return (
		<Container>
			<Row>
				<Col>
					<h1>Viewing Task: {props.task.name}</h1>
					<h2>
						<a href={'/project/' + props.task.project.encodedViewUuid}>
							{props.task.project.name}
						</a>
					</h2>
				</Col>
			</Row>
		</Container>
	)
}

export default ViewTask;