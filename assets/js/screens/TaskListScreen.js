import React, { useState, useEffect } from 'react';
import { ListGroup, Container, Row, Col } from 'react-bootstrap';

const TaskListScreen = props => {
	const [tasks, setTasks] = useState([]);

	const fetchTasks = () => {
		fetch('/api/v1/user/tasks', {
			method: 'POST',
			body: JSON.stringify({
				encodedUserUuid: props.user.encodedUuid,
			}),
		})
			.then(resp => resp.json())
			.then(resp => {
				!resp.error ? setTasks(resp) : console.log(resp);
			});
	};

	useEffect(() => {
		fetchTasks();
	}, []);

	return (
		<Container>
			<Row>
				<Col>
					<ListGroup>
						{tasks.map((task, index) => {
							return (
								<ListGroup.Item key={index}>
									<h5>
										<a
											href={`${window.location.origin}/task/${task.encodedUuid}`}
										>
											{task.name}
										</a>
									</h5>
									<p>
										{task.dueDate
											? `Due: ${task.dueDate}`
											: null}
									</p>
								</ListGroup.Item>
							);
						})}
					</ListGroup>
				</Col>
			</Row>
		</Container>
	);
};

export default TaskListScreen;
