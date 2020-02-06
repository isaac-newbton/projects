import React, { useState, useEffect } from 'react';
import Container from 'react-bootstrap/Container';
import { Row, Col } from 'react-bootstrap';
import { useParams } from 'react-router-dom';
import EditProject from './projectView/EditProject';
import ViewProject from './projectView/ViewProject';

const ProjectScreen = props => {
	const { encodedUuid } = useParams('encodedUuid');
	const [isLoading, setIsLoading] = useState(false) // FIXME: make true when backend api is complete

	const [project, setProject] = useState(null);

	const fetchProject = () => {
		fetch("http://127.0.0.1:8000/api/v1/project/view", {
			method: "POST",
			body: JSON.stringify({
				'encodedUuid' : encodedUuid
			})
		})
		.then(resp => resp.json())
		.then(project => {
			console.log('fetched the project!')
			setProject(project)
			setIsLoading(false)
		})
		.catch(error => {
			return Promise.reject()
		})
	}
	useEffect(fetchProject, []) 

	const updateProjectHandler = event => {
		if (project[event.target.name] !== event.target.value){
			project[event.target.name] = event.target.value;
			setProject(project);
			saveProject()
		}
	}

	const saveProject = () => console.log("update the project with: " + project)

	if (isLoading === true) return "loading..."
	if (project) {
		if (project.edit) return <EditProject updateProject={updateProjectHandler} project={project}/>
		return <ViewProject project={project}/>
	}
	return "TODO: Handle this with a 404 component or something similar"
}

export default ProjectScreen;