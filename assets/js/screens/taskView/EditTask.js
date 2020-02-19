import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import CommentForm from '../../components/CommentForm';
import { ListGroup, Badge } from 'react-bootstrap';
import UserAuthenticatedComponent from '../../components/UserAuthenticated';

const EditTask = props => {

	return (
		<Container>
			<Row>
				<Col>
					<h1>Editing Task: {props.task.name}</h1>
					<h2>
						<a href={'/project/' + props.task.project.encodedUuid}>
							{props.task.project.name}
						</a>
					</h2>
				</Col>
			</Row>
			<Row>
				<Col>
					<UserAuthenticatedComponent>
						<CommentForm handleSubmit={props.HandleCommentFormSubmit}/>
					</UserAuthenticatedComponent>
				</Col>
			</Row>

			<Row>
				<Col>
				<ListGroup>
					{props.task.comments.map((comment,index) => {
						return (
							<ListGroup.Item key={index}>
								<p>{comment.content}</p>
								<span className="small"><b>{comment.user.email ?? comment.user.mobileNumber}</b> at {comment.timestamp}</span>
							</ListGroup.Item>
						)
					})}
				</ListGroup>
				</Col>
			</Row>
		</Container>
	)
}

export default EditTask;