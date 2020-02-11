import React, { useState } from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import ListGroup from 'react-bootstrap/ListGroup';
import { useHistory } from 'react-router-dom';
import Task from '../../components/Task';

const EditProject = props => {
	const history = useHistory()
	const [newTask, setNewTask] = useState('');
	const addTask = event => {
		event.preventDefault();
		//TODO: create the new task using the api
		console.log(newTask)
	}
	const goToHome = () => history.push('/')
	const deleteProject = ()=>{
		const response = fetch(`/api/v1/project/delete/${props.project.encodedEditUuid}`, {
			method: 'DELETE'
		}).then((r)=>{
			if(200===r.status){
				goToHome()
			}
		})
	}
	return (
		<Container>
			<Row>
				<Col>
					{/* <h1 contentEditable value={props.project.name} onChange={event => console.log(event)}></h1> */}
					<Form.Control name="name" onChange={event => props.updateProject(event)} type="text" defaultValue={props.project.name} />
					<Form.Control name="dueDate" onChange={event => props.updateProject(event)} type="date" defaultValue={props.project.dueDate} />
				</Col>
			</Row>
			{/* TODO: Return the tasks here */}
			<Row>
				<Col>
					<ListGroup>
						{props.project.tasks.map(task => <Task key={task.uuid} name={task.name} dueDate={task.dueDate} />)}
					</ListGroup>
				</Col>
			</Row>
			<Row>
				<Col>
					<Form onSubmit={addTask}>
						<Form.Group>
							<Form.Control type="text" placeholder="Add new task" onChange={event => setNewTask(event.target.value)} value={newTask}/>
							<Form.Control type="submit" />
						</Form.Group>
					</Form>
				</Col>
			</Row>
			<Row>
				<Col>
					<Button variant="danger" type="button" onClick={deleteProject}>Delete</Button>
				</Col>
			</Row>
		</Container>
	)
}

export default EditProject;