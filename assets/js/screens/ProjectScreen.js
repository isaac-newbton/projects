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
		fetch("/api/v1/project/view", {
			method: "POST",
			body: JSON.stringify({
				'encodedUuid' : encodedUuid
			})
		})
		.then(resp => resp.json())
		.then(project => {
			setProject(project)
			setIsLoading(false)
		})
		.catch(error => {
			return Promise.reject()
		})
	}
	useEffect(fetchProject, []) //FIXME: commenting this line returns the 404 condition, but it should return the loading condition - why is this?

	const updateProjectHandler = event => {
		if (project[event.target.name] !== event.target.value){
			project[event.target.name] = event.target.value;
			setProject(project);
			saveProject()
		}
	}

	const saveProject = () => {
		fetch("/api/v1/project/update", {
			method: "POST",
			body: JSON.stringify({
				'project' : project
			})
		})
		.then(resp => resp.json())
		.then(project => {
			setProject(project)
			console.log('updated!')
		})
		.catch(error => {
			return Promise.reject()
		})
	}

	if (isLoading === true) return "loading..."
	if (project && 'name' in project) {
		if (project.edit) return <EditProject updateProject={updateProjectHandler} project={project}/>
		return <ViewProject project={project}/>
	}
	return "TODO: Handle this with a 404 component or something similar"
}

export default ProjectScreen;