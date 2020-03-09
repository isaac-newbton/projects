import React, { useState, useEffect } from 'react';
import { ListGroup } from 'react-bootstrap';

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
				console.log(resp);
				!resp.error ? setProjects(resp) : console.log(resp);
			});
	};

	useEffect(() => {
		fetchProjects();
	}, []);

	return (
		<ListGroup>
			{projects.map(project => {
				<ListGroup.Item>
					<h5>{project.name}</h5>
					<p>{project.due}</p>
				</ListGroup.Item>;
			})}
		</ListGroup>
	);
};

export default ProjectsListScreen;
