import React, { useState } from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import ListGroup from 'react-bootstrap/ListGroup';
import { useHistory } from 'react-router-dom';
import Task from '../../components/Task';
import { Badge } from 'react-bootstrap';

const EditProject = props => {
	const history = useHistory();

	const [newTask, setNewTask] = useState({
		name: '',
		dueDate: null,
		projectUuid: props.project.encodedUuid,
	});
	const addTask = event => {
		event.preventDefault();
		fetch('/api/v1/task/create', {
			method: 'POST',
			body: JSON.stringify(newTask),
		})
			.then(resp => resp.json())
			.then(() => {
				setNewTask({ ...newTask, name: '', dueDate: null }); // FIXME: this isn't scalable but i couln't think of a better way to handle it
				props.refreshProject();
			});
	};

	const deleteTask = encodedUuid => {
		fetch('/api/v1/task/delete/' + encodedUuid, {
			method: 'DELETE',
		})
			.then(resp => resp.json())
			.then(() => props.refreshProject());
	};

	const goToHome = () => history.push('/');
	const deleteProject = () => {
		const response = fetch(
			`/api/v1/project/delete/${props.project.encodedUuid}`,
			{
				method: 'DELETE',
			},
		).then(r => {
			if (200 === r.status) {
				goToHome();
			}
		});
	};

	const handleOwnProject = () => {
		fetch('/api/v1/project/owner/add', {
			method: 'POST',
			body: JSON.stringify({
				user: props.user,
				project: props.project,
			}),
		})
			.then(resp => resp.json())
			.then(resp => {
				if (!resp.error) {
					console.log(resp);
					props.refreshProject();
				}
			});
	};
	return (
		<Container>
			<Row>
				<Col>
					{!props.user && !props.project.owner ? (
						<>
							<Badge variant='primary'>
								{window.location.href}
							</Badge>
							<p className='small'>
								Project Url - keep this link to save this
								project after registering!
							</p>
						</>
					) : !props.project.owner ? (
						<Button onClick={() => handleOwnProject()}>
							Take ownership of project
						</Button>
					) : (
						<p>Owner: {props.project.owner.displayName}</p>
					)}
				</Col>
			</Row>
			<Row>
				<Col>
					<Form.Control
						name='name'
						onChange={event => props.updateProject(event)}
						type='text'
						defaultValue={props.project.name}
					/>
					<Form.Control
						name='dueDate'
						onChange={event => props.updateProject(event)}
						type='date'
						defaultValue={props.project.dueDate}
					/>
				</Col>
			</Row>
			<Row>
				<Col>
					<ListGroup>
						{props.project.tasks.map(task => {
							if (task.active) {
								// TODO: refactor me ...someday
								return (
									<div key={task.encodedUuid}>
										<Task task={task} />
										<Button
											variant='danger'
											onClick={() =>
												deleteTask(task.encodedUuid)
											}
											className=''
										>
											delete
										</Button>
									</div>
								);
							}
						})}
					</ListGroup>
				</Col>
			</Row>
			<Row>
				<Col>
					<Form onSubmit={addTask}>
						<Form.Group>
							<Form.Control
								type='text'
								placeholder='Add new task'
								onChange={event =>
									setNewTask({
										...newTask,
										name: event.target.value,
									})
								}
								value={newTask.name}
							/>
							<Form.Control type='submit' value='Add Task' />
						</Form.Group>
					</Form>
				</Col>
			</Row>
			<Row>
				<Col>
					<Button
						variant='danger'
						type='button'
						onClick={deleteProject}
					>
						Delete
					</Button>
				</Col>
			</Row>
		</Container>
	);
};

export default EditProject;
