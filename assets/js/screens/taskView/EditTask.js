import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import CommentForm from '../../components/CommentForm';
import { ListGroup, Badge, Image, Card, Button } from 'react-bootstrap';
import UserAuthenticatedComponent from '../../components/UserAuthenticated';
import FileUploadForm from '../../components/FileUploadForm';
import FilesList from '../../components/FilesList';
import UserSearchForm from '../../components/UserSearchForm';

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
				<Col>
					{props.task.assignedUser ? (
						<>
							<UserAuthenticatedComponent>
								<Button
									onClick={() =>
										props.removeUserHandler(props.task)
									}
									variant='link'
								>
									&times;
								</Button>
							</UserAuthenticatedComponent>
							{props.task.assignedUser.displayName ??
								props.task.assignedUser.email ??
								props.task.assignedUser.mobileNumber}
						</>
					) : (
						<UserAuthenticatedComponent>
							<UserSearchForm
								onSelect={props.assignUserHandler}
							/>
						</UserAuthenticatedComponent>
					)}
				</Col>
			</Row>
			<Row>
				<Col>
					<h5>Attached Files</h5>
					<FilesList files={props.task.files} />
				</Col>
			</Row>
			<Row>
				<Col>
					<UserAuthenticatedComponent>
						<CommentForm
							handleSubmit={props.HandleCommentFormSubmit}
						/>
						<FileUploadForm
							handleSubmit={props.HandleFileUploadSubmit}
						/>
					</UserAuthenticatedComponent>
				</Col>
			</Row>

			<Row>
				<Col>
					<ListGroup>
						{props.task.comments.map((comment, index) => {
							return (
								<ListGroup.Item key={index}>
									<p>{comment.content}</p>
									<span className='small'>
										<b>
											{comment.user.email ??
												comment.user.mobileNumber}
										</b>{' '}
										at {comment.timestamp}
									</span>
								</ListGroup.Item>
							);
						})}
					</ListGroup>
				</Col>
			</Row>
		</Container>
	);
};

export default EditTask;
