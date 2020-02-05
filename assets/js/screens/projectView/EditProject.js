import React, { useState } from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';

const EditProject = props => {
	const [newTask, setNewTask] = useState('');
	const addTask = event => {
		event.preventDefault();
		//TODO: create the new task using the api
		console.log(newTask)
	}
	return (
		<Container>
			<Row>
				<Col>
					<h1>Editing: {props.project.name}</h1>
					<Form.Control name="dueDate" onChange={event => props.updateProject(event)} type="date" defaultValue={props.project.dueDate} />
				</Col>
			</Row>
			<Row>
				<Col>
					{props.project.tasks.map((task, index) => <div key={index}>{task.name}</div>)}
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
		</Container>
	)
}

export default EditProject;