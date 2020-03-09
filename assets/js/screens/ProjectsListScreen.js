import React, { useState, useEffect } from 'react';
import { ListGroup, Container, Row, Col } from 'react-bootstrap';

const ProjectsListScreen = props => {
	const [projects, setProjects] = useState([]);

	const fetchProjects = () => {
		fetch('/api/v1/project/list', {
			method: 'POST',
			body: JSON.stringify({
				encodedUserUuid: props.user.encodedUuid,
			}),
		})
			.then(resp => resp.json())
			.then(resp => {
				!resp.error ? setProjects(resp) : console.log(resp);
			});
	};

	useEffect(() => {
		fetchProjects();
	}, []);

	return (
		<Container>
			<Row>
				<Col>
					<ListGroup>
						{projects.map((project, index) => {
							return (
								<ListGroup.Item key={index}>
									<h5>
										<a
											href={`${window.location.origin}/project/${project.encodedUuid}`}
										>
											{project.name}
										</a>
									</h5>
									<p>
										{project.dueDate
											? `Due: ${project.dueDate}`
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

export default ProjectsListScreen;
